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
import com.bumptech.glide.load.resource.bitmap.RoundedCorners;

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

        // 1. CABECERA Y COLOR
        try {
            v.findViewById(R.id.headerContainer).setBackgroundColor(android.graphics.Color.parseColor(team.getColor()));
        } catch (Exception e) {
            // Color por defecto si falla el hex
            v.findViewById(R.id.headerContainer).setBackgroundColor(android.graphics.Color.parseColor("#192e8a"));
        }

        ((TextView)v.findViewById(R.id.tvDetailName)).setText(team.getName());
        ((TextView)v.findViewById(R.id.tvDetailCar)).setText(team.getCarModel());

        // 2. LOGO DEL EQUIPO (Con protección de conexión)
        ImageView imgLogo = v.findViewById(R.id.imgTeamDetailLogo);
        String logoUrl = team.getLogo();

        // CHIVATO: Mira el Logcat filtrando por "DEBUG_IMG" para ver la URL real
        if (logoUrl != null) android.util.Log.d("DEBUG_IMG", "Logo URL: " + logoUrl);

        if (logoUrl != null && !logoUrl.isEmpty()) {
            com.bumptech.glide.load.model.GlideUrl urlWithHeaders = new com.bumptech.glide.load.model.GlideUrl(
                    logoUrl,
                    new com.bumptech.glide.load.model.LazyHeaders.Builder()
                            .addHeader("Connection", "close")
                            .build()
            );

            Glide.with(this)
                    .load(urlWithHeaders)
                    // CAMBIO: Forzamos el orden: Primero CenterCrop (llenar), luego Redondear
                    .apply(com.bumptech.glide.request.RequestOptions.bitmapTransform(
                            new com.bumptech.glide.load.resource.bitmap.CenterCrop()
                    ))
                    .transform(new com.bumptech.glide.load.resource.bitmap.RoundedCorners(48))
                    // ----------------------------------------------------

                    // AÑADE ESTO PARA VER EL ERROR EXACTO SI FALLA OTRA VEZ
                    .listener(new com.bumptech.glide.request.RequestListener<android.graphics.drawable.Drawable>() {
                        @Override
                        public boolean onLoadFailed(@androidx.annotation.Nullable com.bumptech.glide.load.engine.GlideException e, Object model, com.bumptech.glide.request.target.Target<android.graphics.drawable.Drawable> target, boolean isFirstResource) {
                            android.util.Log.e("GLIDE_ERROR", "Fallo logo: " + e.getMessage());
                            if (e != null) e.logRootCauses("GLIDE_ERROR"); // <--- MIRA ESTO EN EL LOGCAT
                            return false;
                        }
                        @Override
                        public boolean onResourceReady(android.graphics.drawable.Drawable resource, Object model, com.bumptech.glide.request.target.Target<android.graphics.drawable.Drawable> target, com.bumptech.glide.load.DataSource dataSource, boolean isFirstResource) {
                            return false;
                        }
                    })
                    .placeholder(R.drawable.circle_bg_gray)
                    .error(android.R.drawable.ic_delete)
                    .into(imgLogo);

            imgLogo.setVisibility(View.VISIBLE);
        } else {
            imgLogo.setImageResource(R.drawable.ic_menu_grid); // Icono por defecto
        }

        // 3. BIO (Biografía)
        TextView tvBio = v.findViewById(R.id.tvTeamBio);
        if (team.getBio() != null && !team.getBio().isEmpty()) {
            tvBio.setText(team.getBio());
        } else {
            tvBio.setText("No biography available.");
        }

        // 4. STATS (Estadísticas)
        setStat(v.findViewById(R.id.statDrivers), "DRIVERS", String.valueOf(team.getStats().activeDrivers));
        setStat(v.findViewById(R.id.statWins), "WINS", String.valueOf(team.getStats().wins));
        setStat(v.findViewById(R.id.statPodiums), "PODIUMS", String.valueOf(team.getStats().podiums));
        setStat(v.findViewById(R.id.statPoints), "POINTS", String.valueOf(team.getStats().totalPoints));

        // 5. ROSTER (Lista de pilotos)
        androidx.recyclerview.widget.RecyclerView rv = v.findViewById(R.id.recyclerRoster);
        rv.setLayoutManager(new LinearLayoutManager(getContext()));
        rv.setAdapter(new RosterAdapter(getContext(), team.getRoster()));

        // 6. LIVERY (Imagen del coche - Con protección de conexión)
        ImageView imgLivery = v.findViewById(R.id.imgLivery);
        String liveryUrl = team.getLiveryImage();

        if (liveryUrl != null) android.util.Log.d("DEBUG_IMG", "Livery URL: " + liveryUrl);

        if (liveryUrl != null && !liveryUrl.isEmpty()) {
            com.bumptech.glide.load.model.GlideUrl urlWithHeaders = new com.bumptech.glide.load.model.GlideUrl(
                    liveryUrl,
                    new com.bumptech.glide.load.model.LazyHeaders.Builder()
                            .addHeader("Connection", "close")
                            .build()
            );

            Glide.with(this)
                    .load(urlWithHeaders)
                    .placeholder(android.R.color.darker_gray)
                    .into(imgLivery);
        }

        // 7. SPECS (Especificaciones Técnicas)
        if (team.getSpecs() != null) {
            setText(v, R.id.tvSpecChassis, team.getSpecs().chassis);
            setText(v, R.id.tvSpecEngine, team.getSpecs().engine);
            setText(v, R.id.tvSpecPower, team.getSpecs().power);
            setText(v, R.id.tvSpecLayout, team.getSpecs().layout); // Layout, no Weight
            setText(v, R.id.tvSpecGearbox, team.getSpecs().gearbox);
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