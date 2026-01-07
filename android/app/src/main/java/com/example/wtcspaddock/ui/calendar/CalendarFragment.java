package com.example.wtcspaddock.ui.calendar;

import android.os.Bundle;
import android.os.CountDownTimer; // IMPORTANTE
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.bumptech.glide.Glide;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.CalendarResponse;
import com.example.wtcspaddock.models.Race;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.concurrent.TimeUnit; // IMPORTANTE

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class CalendarFragment extends Fragment {

    private TextView tvTrackName, tvLayout, tvNextRaceLabel, tvCountdown;
    private ImageView imgTrack;

    // VARIABLE PARA EL TIMER (Para poder cancelarlo luego)
    private CountDownTimer raceTimer;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_calendar, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        tvTrackName = view.findViewById(R.id.tvTrackName);
        tvLayout = view.findViewById(R.id.tvLayout);
        tvNextRaceLabel = view.findViewById(R.id.lblNextRace);
        tvCountdown = view.findViewById(R.id.tvCountdown);
        imgTrack = view.findViewById(R.id.imgTrack);

        loadRaceData();
    }

    // ... (El método loadRaceData se queda IGUAL que antes) ...
    // ... (El método findAndShowNextRace se queda IGUAL que antes) ...

    // COPIA ESTOS MÉTODOS DE ABAJO:

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
            }
            @Override
            public void onFailure(Call<CalendarResponse> call, Throwable t) { Log.e("API", t.getMessage()); }
        });
    }

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

        // INICIAR EL TIMER
        startRealTimeCountdown(race.getDate());
    }

    private void startRealTimeCountdown(String raceDateString) {
        // 1. Si ya había un timer corriendo, lo matamos para no tener dos a la vez
        if (raceTimer != null) {
            raceTimer.cancel();
        }

        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
        try {
            Date raceDate = sdf.parse(raceDateString);
            Date now = new Date();

            if (raceDate == null) return;

            long diffInMillis = raceDate.getTime() - now.getTime();

            if (diffInMillis > 0) {
                // 2. CREAMOS EL TIMER (Cuenta atrás desde 'diffInMillis', actualizando cada 1000ms)
                raceTimer = new CountDownTimer(diffInMillis, 1000) {
                    @Override
                    public void onTick(long millisUntilFinished) {
                        // Cálculos matemáticos
                        long days = TimeUnit.MILLISECONDS.toDays(millisUntilFinished);
                        long hours = TimeUnit.MILLISECONDS.toHours(millisUntilFinished) % 24;
                        long minutes = TimeUnit.MILLISECONDS.toMinutes(millisUntilFinished) % 60;
                        long seconds = TimeUnit.MILLISECONDS.toSeconds(millisUntilFinished) % 60;

                        // Formato: 3D 02H 14M 45S
                        String timeString = String.format(Locale.getDefault(),
                                "%dD %02dH %02dM %02dS",
                                days, hours, minutes, seconds);

                        tvCountdown.setText(timeString);
                    }

                    @Override
                    public void onFinish() {
                        tvCountdown.setText("RACE STARTED!");
                        tvNextRaceLabel.setText("LIVE NOW");
                    }
                }.start(); // ¡Arranca!

            } else {
                tvCountdown.setText("COMPLETED");
            }

        } catch (ParseException e) {
            e.printStackTrace();
        }
    }

    // 3. MUY IMPORTANTE: Limpieza de memoria
    // Si el usuario cambia de pestaña (se va a Profile), paramos el reloj.
    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if (raceTimer != null) {
            raceTimer.cancel();
        }
    }
}