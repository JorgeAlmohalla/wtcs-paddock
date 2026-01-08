package com.example.wtcspaddock.ui.standings;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.DriverStanding;
import com.example.wtcspaddock.models.ManufacturerStanding;
import com.example.wtcspaddock.models.TeamStanding;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class StandingsListFragment extends Fragment {

    private String type; // "drivers", "constructors", "manufacturers"
    private RecyclerView recyclerView;

    public static StandingsListFragment newInstance(String type) {
        StandingsListFragment fragment = new StandingsListFragment();
        Bundle args = new Bundle();
        args.putString("TYPE", type);
        fragment.setArguments(args);
        return fragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_calendar_list, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        if (getArguments() != null) type = getArguments().getString("TYPE");

        recyclerView = view.findViewById(R.id.recyclerCalendar);
        recyclerView.setLayoutManager(new LinearLayoutManager(getContext()));

        loadData();
    }

    private void loadData() {
        if ("drivers".equals(type)) {
            loadDrivers();
        } else if ("constructors".equals(type)) {
            loadTeams();
        } else if ("manufacturers".equals(type)) {
            loadManufacturers();
        }
    }

    // --- CARGA DE DRIVERS ---
    private void loadDrivers() {
        RetrofitClient.getApiService().getDriverStandings().enqueue(new Callback<List<DriverStanding>>() {
            @Override
            public void onResponse(Call<List<DriverStanding>> call, Response<List<DriverStanding>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Object> list = new ArrayList<>(response.body());
                    recyclerView.setAdapter(new StandingsAdapter(list));
                }
            }
            @Override
            public void onFailure(Call<List<DriverStanding>> call, Throwable t) { Log.e("API", "Err drivers"); }
        });
    }

    // --- CARGA DE TEAMS ---
    private void loadTeams() {
        RetrofitClient.getApiService().getTeamStandings().enqueue(new Callback<List<TeamStanding>>() {
            @Override
            public void onResponse(Call<List<TeamStanding>> call, Response<List<TeamStanding>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Object> list = new ArrayList<>(response.body());
                    recyclerView.setAdapter(new StandingsAdapter(list));
                }
            }
            @Override
            public void onFailure(Call<List<TeamStanding>> call, Throwable t) { Log.e("API", "Err teams"); }
        });
    }

    // --- CARGA DE MANUFACTURERS ---
    private void loadManufacturers() {
        RetrofitClient.getApiService().getManufacturerStandings().enqueue(new Callback<List<ManufacturerStanding>>() {
            @Override
            public void onResponse(Call<List<ManufacturerStanding>> call, Response<List<ManufacturerStanding>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Object> list = new ArrayList<>(response.body());
                    recyclerView.setAdapter(new StandingsAdapter(list));
                }
            }
            @Override
            public void onFailure(Call<List<ManufacturerStanding>> call, Throwable t) { Log.e("API", "Err manu"); }
        });
    }
}