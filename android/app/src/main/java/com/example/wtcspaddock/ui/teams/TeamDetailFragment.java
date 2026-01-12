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

        // Cabecera
        v.findViewById(R.id.headerContainer).setBackgroundColor(Color.parseColor(team.getColor()));
        ((TextView)v.findViewById(R.id.tvDetailName)).setText(team.getName());
        ((TextView)v.findViewById(R.id.tvDetailCar)).setText(team.getCarModel());

        // Stats
        setStat(v.findViewById(R.id.statDrivers), "DRIVERS", String.valueOf(team.getStats().activeDrivers));
        setStat(v.findViewById(R.id.statWins), "WINS", String.valueOf(team.getStats().wins));
        setStat(v.findViewById(R.id.statPodiums), "PODIUMS", String.valueOf(team.getStats().podiums));
        setStat(v.findViewById(R.id.statPoints), "POINTS", String.valueOf(team.getStats().totalPoints));

        // Roster
        RecyclerView rv = v.findViewById(R.id.recyclerRoster);
        rv.setLayoutManager(new LinearLayoutManager(getContext()));
        rv.setAdapter(new RosterAdapter(getContext(), team.getRoster()));

        // Livery
        ImageView imgLivery = v.findViewById(R.id.imgLivery);
        if (team.getLiveryImage() != null) {
            Glide.with(this).load(team.getLiveryImage()).into(imgLivery);
        }

        // Specs
        ((TextView)v.findViewById(R.id.tvSpecEngine)).setText("Engine: " + team.getSpecs().engine);
        ((TextView)v.findViewById(R.id.tvSpecPower)).setText("Power: " + team.getSpecs().power);
        ((TextView)v.findViewById(R.id.tvSpecWeight)).setText("Weight: " + team.getSpecs().weight);
    }

    private void setStat(View view, String label, String value) {
        ((TextView)view.findViewById(R.id.tvStatLabel)).setText(label);
        ((TextView)view.findViewById(R.id.tvStatValue)).setText(value);
    }
}