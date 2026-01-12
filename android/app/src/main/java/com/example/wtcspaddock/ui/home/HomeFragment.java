package com.example.wtcspaddock.ui.home;

import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
// Importante para la barra de carga
import android.widget.ProgressBar;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.bumptech.glide.Glide;
import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.CalendarResponse;
import com.example.wtcspaddock.models.DriverStanding;
import com.example.wtcspaddock.models.Race;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.concurrent.TimeUnit;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HomeFragment extends Fragment {

    private TextView tvTrackName, tvLayout, tvNextRaceLabel, tvCountdown;
    private TextView tvLeaderName, tvLeaderTeam, tvLeaderPoints;
    private ImageView imgTrack;
    private CountDownTimer raceTimer;

    // --- VARIABLES DE CARGA ---
    private View contentLayout;
    private ProgressBar progressBar;
    private int apiCallsPending = 0; // Contador de peticiones

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_home, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Vincular Controles de Carga
        contentLayout = view.findViewById(R.id.contentLayout);
        progressBar = view.findViewById(R.id.progressBar);

        // Vincular Vistas Home
        tvTrackName = view.findViewById(R.id.tvTrackName);
        tvLayout = view.findViewById(R.id.tvLayout);
        tvNextRaceLabel = view.findViewById(R.id.lblNextRace);
        tvCountdown = view.findViewById(R.id.tvCountdown);
        imgTrack = view.findViewById(R.id.imgTrack);

        tvLeaderName = view.findViewById(R.id.tvLeaderName);
        tvLeaderTeam = view.findViewById(R.id.tvLeaderTeam);
        tvLeaderPoints = view.findViewById(R.id.tvLeaderPoints);

        // Click Listeners
        View cardNextRace = view.findViewById(R.id.cardNextRace);
        if (cardNextRace != null) {
            cardNextRace.setOnClickListener(v -> {
                if (getActivity() instanceof MainActivity) ((MainActivity) getActivity()).navigateToCalendar();
            });
        }

        View cardLeader = view.findViewById(R.id.cardLeader);
        if (cardLeader != null) {
            cardLeader.setOnClickListener(v -> {
                if (getActivity() instanceof MainActivity) ((MainActivity) getActivity()).navigateToStandings();
            });
        }

        // --- INICIAR CARGA DE DATOS ---
        startLoadingData();
    }

    private void startLoadingData() {
        // Estado inicial: Cargando
        progressBar.setVisibility(View.VISIBLE);
        contentLayout.setVisibility(View.INVISIBLE);

        apiCallsPending = 2; // Tenemos 2 peticiones que hacer

        loadRaceData();
        loadStandingsData();
    }

    // Método que se llama cuando termina CUALQUIER petición
    private void checkLoadingComplete() {
        apiCallsPending--;
        if (apiCallsPending <= 0) {
            // Ya han terminado todas (con éxito o error)
            if (progressBar != null) progressBar.setVisibility(View.GONE);
            if (contentLayout != null) contentLayout.setVisibility(View.VISIBLE);
        }
    }

    private void loadRaceData() {
        RetrofitClient.getApiService().getCalendar().enqueue(new Callback<CalendarResponse>() {
            @Override
            public void onResponse(Call<CalendarResponse> call, Response<CalendarResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Race> allRaces = response.body().getData();
                    if (allRaces != null && !allRaces.isEmpty()) {
                        findAndShowNextRace(allRaces);
                    }
                }
                checkLoadingComplete(); // <--- IMPORTANTE
            }

            @Override
            public void onFailure(Call<CalendarResponse> call, Throwable t) {
                Log.e("API", "Error calendar: " + t.getMessage());
                checkLoadingComplete(); // <--- IMPORTANTE
            }
        });
    }

    private void loadStandingsData() {
        RetrofitClient.getApiService().getDriverStandings().enqueue(new Callback<List<DriverStanding>>() {
            @Override
            public void onResponse(Call<List<DriverStanding>> call, Response<List<DriverStanding>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<DriverStanding> standings = response.body();
                    if (!standings.isEmpty()) {
                        updateLeaderCard(standings.get(0));
                    }
                }
                checkLoadingComplete(); // <--- IMPORTANTE
            }

            @Override
            public void onFailure(Call<List<DriverStanding>> call, Throwable t) {
                Log.e("API", "Error standings: " + t.getMessage());
                checkLoadingComplete(); // <--- IMPORTANTE
            }
        });
    }

    // ... Resto de métodos (findAndShowNextRace, updateUI, timer...) siguen igual ...
    // COPIA AQUÍ TUS MÉTODOS DE LÓGICA (findAndShowNextRace, updateUI, startRealTimeCountdown, updateLeaderCard, onDestroyView)
    // Si no los tienes a mano, dímelo y te los pego de nuevo.

    private void findAndShowNextRace(List<Race> races) {
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
        Date today = new Date();
        Race nextRace = null;

        for (Race race : races) {
            try {
                Date raceDate = sdf.parse(race.getDate());
                if (raceDate != null && raceDate.after(today)) {
                    nextRace = race;
                    break;
                }
            } catch (ParseException e) { e.printStackTrace(); }
        }

        if (nextRace == null && !races.isEmpty()) nextRace = races.get(races.size() - 1);
        if (nextRace != null) updateUI(nextRace);
    }

    private void updateUI(Race race) {
        tvTrackName.setText(race.getTrackName());
        tvLayout.setText(race.getTitle());

        if (getContext() != null) {
            Glide.with(this)
                    .load(race.getImageUrl())
                    .centerCrop()
                    .placeholder(android.R.drawable.ic_menu_gallery)
                    .into(imgTrack);
        }
        startRealTimeCountdown(race.getDate());
    }

    private void startRealTimeCountdown(String raceDateString) {
        if (raceTimer != null) raceTimer.cancel();

        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
        try {
            Date raceDate = sdf.parse(raceDateString);
            Date now = new Date();
            if (raceDate == null) return;

            long diffInMillis = raceDate.getTime() - now.getTime();

            if (diffInMillis > 0) {
                raceTimer = new CountDownTimer(diffInMillis, 1000) {
                    @Override
                    public void onTick(long millisUntilFinished) {
                        long days = TimeUnit.MILLISECONDS.toDays(millisUntilFinished);
                        long hours = TimeUnit.MILLISECONDS.toHours(millisUntilFinished) % 24;
                        long minutes = TimeUnit.MILLISECONDS.toMinutes(millisUntilFinished) % 60;
                        long seconds = TimeUnit.MILLISECONDS.toSeconds(millisUntilFinished) % 60;
                        String timeString = String.format(Locale.getDefault(), "%02dD %02dH %02dM %02dS", days, hours, minutes, seconds);
                        tvCountdown.setText(timeString);
                    }
                    @Override
                    public void onFinish() { tvCountdown.setText("RACE STARTED!"); }
                }.start();
            } else {
                tvCountdown.setText("COMPLETED");
            }
        } catch (ParseException e) { e.printStackTrace(); }
    }

    private void updateLeaderCard(DriverStanding leader) {
        tvLeaderName.setText(leader.getName());
        tvLeaderTeam.setText(leader.getTeam());
        tvLeaderPoints.setText(leader.getPoints() + " PTS");
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if (raceTimer != null) raceTimer.cancel();
    }
}