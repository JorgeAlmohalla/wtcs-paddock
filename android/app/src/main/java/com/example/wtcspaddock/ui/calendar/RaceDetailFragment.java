package com.example.wtcspaddock.ui.calendar;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.viewpager2.adapter.FragmentStateAdapter;
import androidx.viewpager2.widget.ViewPager2;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.ResultRow;
import com.example.wtcspaddock.models.RoundDetailResponse;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RaceDetailFragment extends Fragment {

    private static final String ARG_ROUND_ID = "round_id";
    private static final String ARG_TRACK_NAME = "track_name";

    private int roundId;
    private String trackName;
    private RoundDetailResponse roundData; // Datos descargados

    // Método para crear el fragmento con datos
    public static RaceDetailFragment newInstance(int roundId, String trackName) {
        RaceDetailFragment fragment = new RaceDetailFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_ROUND_ID, roundId);
        args.putString(ARG_TRACK_NAME, trackName);
        fragment.setArguments(args);
        return fragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Reutilizamos el XML que ya diseñaste para la actividad
        return inflater.inflate(R.layout.activity_race_detail, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        if (getArguments() != null) {
            roundId = getArguments().getInt(ARG_ROUND_ID);
            trackName = getArguments().getString(ARG_TRACK_NAME);
        }

        // 1. Vincular Cabecera
        TextView tvTrack = view.findViewById(R.id.tvDetailTrack);
        TextView tvRound = view.findViewById(R.id.tvDetailRound);
        tvTrack.setText(trackName);
        tvRound.setText("ROUND " + roundId);

        // 2. Cargar datos
        loadRoundData(roundId);
    }

    private void loadRoundData(int roundId) {
        RetrofitClient.getApiService().getRoundDetails(roundId).enqueue(new Callback<RoundDetailResponse>() {
            @Override
            public void onResponse(Call<RoundDetailResponse> call, Response<RoundDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null && isAdded()) {
                    roundData = response.body();
                    setupTabs(); // Configuramos las pestañas cuando llegan los datos
                }
            }

            @Override
            public void onFailure(Call<RoundDetailResponse> call, Throwable t) {
                if (isAdded()) {
                    Log.e("API", "Error: " + t.getMessage());
                    Toast.makeText(getContext(), "Error de carga", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    private void setupTabs() {
        // Asegurarnos de que la vista sigue existiendo
        if (getView() == null) return;

        TabLayout tabLayout = getView().findViewById(R.id.tabLayout);
        ViewPager2 viewPager = getView().findViewById(R.id.viewPager);

        // Pasamos 'this' (el fragmento) al adaptador
        ViewPagerAdapter adapter = new ViewPagerAdapter(this);
        viewPager.setAdapter(adapter);

        new TabLayoutMediator(tabLayout, viewPager, (tab, position) -> {
            switch (position) {
                case 0: tab.setText("QUALIFYING"); break;
                case 1: tab.setText("SPRINT RACE"); break;
                case 2: tab.setText("FEATURE RACE"); break;
            }
        }).attach();
    }

    // Método público para que los hijos pidan datos
    public List<ResultRow> getSessionData(String type) {
        if (roundData == null) return null;
        switch (type) {
            case "qualy": return roundData.getQualifying();
            case "sprint": return roundData.getSprintResults();
            case "feature": return roundData.getFeatureResults();
            default: return null;
        }
    }

    // --- ADAPTADOR VIEW PAGER (Interno) ---
    class ViewPagerAdapter extends FragmentStateAdapter {
        public ViewPagerAdapter(@NonNull Fragment fragment) {
            super(fragment);
        }

        @NonNull
        @Override
        public Fragment createFragment(int position) {
            String type = "qualy";
            if (position == 1) type = "sprint";
            if (position == 2) type = "feature";

            // Reusamos el RaceSessionFragment que ya tenías
            return RaceSessionFragment.newInstance(type);
        }

        @Override
        public int getItemCount() { return 3; }
    }
}