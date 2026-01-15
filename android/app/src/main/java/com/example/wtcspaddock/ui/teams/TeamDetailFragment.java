package com.example.wtcspaddock.ui.teams;

import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.TeamDetailResponse;

import com.bumptech.glide.load.model.GlideUrl;
import com.bumptech.glide.load.model.LazyHeaders;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class TeamDetailFragment extends Fragment {
    private static final String ARG_TEAM_ID = "team_id";
    private int teamId;

    public static TeamDetailFragment newInstance(int teamId) {
        TeamDetailFragment f = new TeamDetailFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_TEAM_ID, teamId);
        f.setArguments(args);
        return f;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_team_detail, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        if (getArguments() != null) teamId = getArguments().getInt(ARG_TEAM_ID);

        loadData();
    }

    private void loadData() {
        RetrofitClient.getApiService().getTeamDetails(teamId).enqueue(new Callback<TeamDetailResponse>() {
            @Override
            public void onResponse(Call<TeamDetailResponse> call, Response<TeamDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    updateUI(response.body());
                }
            }
            @Override
            public void onFailure(Call<TeamDetailResponse> call, Throwable t) {}
        });
    }

    private void updateUI(TeamDetailResponse team) {
        View v = getView();
        if (v == null) return;

        // 1. Cabecera y Color
        v.findViewById(R.id.headerContainer).setBackgroundColor(android.graphics.Color.parseColor(team.getColor()));
        ((TextView)v.findViewById(R.id.tvDetailName)).setText(team.getName());
        ((TextView)v.findViewById(R.id.tvDetailCar)).setText(team.getCarModel());

        // 2. Stats (Big Numbers)
        setStat(v.findViewById(R.id.statDrivers), "DRIVERS", String.valueOf(team.getStats().activeDrivers));
        setStat(v.findViewById(R.id.statWins), "WINS", String.valueOf(team.getStats().wins));
        setStat(v.findViewById(R.id.statPodiums), "PODIUMS", String.valueOf(team.getStats().podiums));
        setStat(v.findViewById(R.id.statPoints), "POINTS", String.valueOf(team.getStats().totalPoints));

        // 3. Roster (Lista de pilotos)
        androidx.recyclerview.widget.RecyclerView rv = v.findViewById(R.id.recyclerRoster);
        rv.setLayoutManager(new LinearLayoutManager(getContext()));
        rv.setAdapter(new RosterAdapter(getContext(), team.getRoster()));

        // 4. Livery (Foto del coche)
        ImageView imgLivery = v.findViewById(R.id.imgLivery);
        if (team.getLiveryImage() != null) {
            Glide.with(this).load(team.getLiveryImage()).into(imgLivery);
        }

        // 5. SPECS (CORREGIDO Y COMPLETO)
        // Usamos el helper setText para no repetir código y evitar nulos
        // Fíjate que ya NO ponemos "Engine: " delante, solo el valor.
        setText(v, R.id.tvSpecChassis, team.getSpecs().chassis);
        setText(v, R.id.tvSpecEngine, team.getSpecs().engine);
        setText(v, R.id.tvSpecPower, team.getSpecs().power);
        setText(v, R.id.tvSpecLayout, team.getSpecs().layout);
        setText(v, R.id.tvSpecGearbox, team.getSpecs().gearbox);

        // --- LOGO (NUEVO) ---
        ImageView imgLogo = v.findViewById(R.id.imgTeamDetailLogo);
        String urlString = team.getLogo();

        if (urlString != null) {
            // 1. TRUCO DE EXPERTO: Crear una GlideUrl con cabeceras personalizadas
            GlideUrl urlWithHeaders = new GlideUrl(urlString, new LazyHeaders.Builder()
                    .addHeader("Connection", "close") // <--- ESTO SOLUCIONA EL ERROR
                    .build());

            Glide.with(this)
                    .load(urlWithHeaders) // Usamos el objeto urlWithHeaders en vez del string directo
                    .error(android.R.drawable.ic_delete)
                    .into(imgLogo);

            imgLogo.setVisibility(View.VISIBLE);
        } else {
            imgLogo.setImageResource(R.drawable.ic_menu_grid);
        }

        // --- BIO (NUEVO) ---
        TextView tvBio = v.findViewById(R.id.tvTeamBio);
        if (team.getBio() != null && !team.getBio().isEmpty()) {
            tvBio.setText(team.getBio());
        } else {
            tvBio.setText("No biography available.");
        }
    }

    // Asegúrate de tener este helper al final de la clase:
    private void setText(View root, int id, String text) {
        TextView tv = root.findViewById(id);
        if (tv != null) {
            tv.setText((text != null && !text.isEmpty()) ? text : "-");
        }
    }

    private void setStat(View view, String label, String value) {
        ((TextView)view.findViewById(R.id.tvStatLabel)).setText(label);
        ((TextView)view.findViewById(R.id.tvStatValue)).setText(value);
    }
}