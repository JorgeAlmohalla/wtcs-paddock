package com.example.wtcspaddock.ui.calendar;

import android.os.Bundle;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.viewpager2.adapter.FragmentStateAdapter;
import androidx.viewpager2.widget.ViewPager2;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.ResultRow;
import com.example.wtcspaddock.models.RoundDetailResponse;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RaceDetailActivity extends AppCompatActivity {

    private TextView tvTrack, tvRound;
    private RoundDetailResponse roundData;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_race_detail);

        // 1. Recibir datos del Intent
        String trackName = getIntent().getStringExtra("TRACK_NAME");
        int round = getIntent().getIntExtra("ROUND_NUM", 1);

        // 2. Vincular Header
        tvTrack = findViewById(R.id.tvDetailTrack);
        tvRound = findViewById(R.id.tvDetailRound);
        tvTrack.setText(trackName);
        tvRound.setText("ROUND " + round);

        // 3. Configurar Pestañas (Tabs)
        TabLayout tabLayout = findViewById(R.id.tabLayout);
        ViewPager2 viewPager = findViewById(R.id.viewPager);

        // Configurar el adaptador de páginas
        ViewPagerAdapter adapter = new ViewPagerAdapter(this);
        viewPager.setAdapter(adapter);

        // Conectar Tabs con ViewPager
        new TabLayoutMediator(tabLayout, viewPager, (tab, position) -> {
            switch (position) {
                case 0: tab.setText("QUALIFYING"); break;
                case 1: tab.setText("SPRINT RACE"); break;
                case 2: tab.setText("FEATURE RACE"); break;
            }
        }).attach();

        int roundNum = getIntent().getIntExtra("ROUND_NUM", 1);
        loadRoundData(roundNum);
    }

    // --- ADAPTADOR DE PÁGINAS (Inner Class) ---
    // Gestiona qué fragmento mostrar en cada pestaña
    class ViewPagerAdapter extends FragmentStateAdapter {
        public ViewPagerAdapter(@NonNull AppCompatActivity fragmentActivity) {
            super(fragmentActivity);
        }

        @NonNull
        @Override
        public androidx.fragment.app.Fragment createFragment(int position) {
            // Aquí pasamos el tipo de sesión al fragmento para que sepa qué cargar
            String sessionType = "qualy";
            if (position == 1) sessionType = "sprint";
            if (position == 2) sessionType = "feature";

            return RaceSessionFragment.newInstance(sessionType);
        }

        @Override
        public int getItemCount() { return 3; } // 3 Pestañas
    }

    private void loadRoundData(int roundId) {
        RetrofitClient.getApiService().getRoundDetails(roundId).enqueue(new Callback<RoundDetailResponse>() {
            @Override
            public void onResponse(Call<RoundDetailResponse> call, Response<RoundDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // 1. Guardamos los datos en la variable global
                    roundData = response.body();

                    // 2. Referenciamos los elementos de la vista (por si acaso no son globales)
                    ViewPager2 viewPager = findViewById(R.id.viewPager);
                    TabLayout tabLayout = findViewById(R.id.tabLayout);

                    // 3. TRUCO CLAVE: Reasignamos el adaptador.
                    // Esto obliga a destruir los fragmentos viejos (vacíos) y crear nuevos
                    // que ahora SÍ leerán 'roundData' correctamente.
                    ViewPagerAdapter adapter = new ViewPagerAdapter(RaceDetailActivity.this);
                    viewPager.setAdapter(adapter);

                    // 4. Volvemos a conectar las pestañas al ViewPager
                    new TabLayoutMediator(tabLayout, viewPager, (tab, position) -> {
                        switch (position) {
                            case 0: tab.setText("QUALIFYING"); break;
                            case 1: tab.setText("SPRINT RACE"); break;
                            case 2: tab.setText("FEATURE RACE"); break;
                        }
                    }).attach();

                } else {
                    // Error de API (404, 500...)
                    android.widget.Toast.makeText(RaceDetailActivity.this, "Error cargando datos", android.widget.Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<RoundDetailResponse> call, Throwable t) {
                android.util.Log.e("API_ERROR", "Fallo red: " + t.getMessage());
                android.widget.Toast.makeText(RaceDetailActivity.this, "Error de conexión", android.widget.Toast.LENGTH_SHORT).show();
            }
        });
    }

    // Este método lo llaman los fragmentos hijos
    public List<ResultRow> getSessionData(String type) {
        if (roundData == null) return null; // Si aún no ha llegado la API, devolvemos null

        switch (type) {
            case "qualy": return roundData.getQualifying();
            case "sprint": return roundData.getSprintResults();
            case "feature": return roundData.getFeatureResults();
            default: return null;
        }
    }

}