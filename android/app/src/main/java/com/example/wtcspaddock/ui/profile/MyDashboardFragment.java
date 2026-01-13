package com.example.wtcspaddock.ui.profile;

import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.DriverDetailResponse;
import com.example.wtcspaddock.models.DriverHistory;
import com.example.wtcspaddock.ui.drivers.HistoryAdapter;
import com.example.wtcspaddock.utils.SessionManager;
import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MyDashboardFragment extends Fragment {

    private TextView tvName, tvTeam, tvBio, tvInput;
    private ImageView imgAvatar;

    // Stats Views
    private View statStarts, statWins, statPoints;

    // Loading Views
    private ProgressBar progressBar;
    private View contentLayout;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_my_dashboard, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Vincular Controles Base
        contentLayout = view.findViewById(R.id.contentLayout);
        progressBar = view.findViewById(R.id.progressBar);

        tvName = view.findViewById(R.id.tvMyName);
        tvTeam = view.findViewById(R.id.tvMyTeam);
        tvBio = view.findViewById(R.id.tvMyBio);
        tvInput = view.findViewById(R.id.tvMyInput);
        imgAvatar = view.findViewById(R.id.imgMyAvatar);

        // Vincular Stats
        statStarts = view.findViewById(R.id.statStarts);
        statWins = view.findViewById(R.id.statWins);
        statPoints = view.findViewById(R.id.statPoints);

        // Cargar mis datos
        SessionManager session = new SessionManager(requireContext());

        // Botones
        view.findViewById(R.id.btnEditProfile).setOnClickListener(v -> {
            // NAVEGAR A LA PANTALLA DE EDICIÓN COMPLETA
            getParentFragmentManager().beginTransaction()
                    .replace(R.id.fragment_container, new EditProfileFragment()) // Cargamos el nuevo fragmento
                    .addToBackStack(null) // Importante: para poder volver atrás al guardar o cancelar
                    .commit();
        });

        view.findViewById(R.id.btnReport).setOnClickListener(v ->
                Toast.makeText(getContext(), "Report system ready", Toast.LENGTH_SHORT).show()
        );
    }

    private void loadMyData(int myId) {
        progressBar.setVisibility(View.VISIBLE);
        contentLayout.setVisibility(View.INVISIBLE);

        // Usamos el MISMO endpoint que el perfil público, ya que trae stats y history
        RetrofitClient.getApiService().getDriverDetails(myId).enqueue(new Callback<DriverDetailResponse>() {
            @Override
            public void onResponse(Call<DriverDetailResponse> call, Response<DriverDetailResponse> response) {
                progressBar.setVisibility(View.GONE);
                contentLayout.setVisibility(View.VISIBLE);

                if (response.isSuccessful() && response.body() != null) {
                    updateUI(response.body());
                }
            }
            @Override
            public void onFailure(Call<DriverDetailResponse> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                Log.e("API", "Error dashboard: " + t.getMessage());
            }
        });
    }

    private void updateUI(DriverDetailResponse data) {
        DriverDetailResponse.DriverInfo info = data.getDriver();
        DriverDetailResponse.DriverStats stats = data.getStats();

        // 1. Cabecera
        tvName.setText(info.getName());
        tvTeam.setText(info.getTeam());
        tvInput.setText(info.getEquipment() != null ? info.getEquipment().toUpperCase() : "-");

        if (info.getBio() != null && !info.getBio().isEmpty()) {
            tvBio.setText(info.getBio());
        } else {
            tvBio.setText("No bio added yet.");
        }

        if (info.getAvatar() != null) {
            Glide.with(this).load(info.getAvatar()).apply(RequestOptions.circleCropTransform()).into(imgAvatar);
        }

        // 2. Stats
        setStat(statStarts, "STARTS", String.valueOf(stats.getStarts()));
        setStat(statWins, "WINS", String.valueOf(stats.getWins()));
        setStat(statPoints, "POINTS", String.valueOf(stats.getPoints()));

        // 3. Gráficos
        setupCharts(data.getHistory());

        // 4. Tabla Historial
        RecyclerView recyclerHistory = getView().findViewById(R.id.recyclerHistory);
        recyclerHistory.setLayoutManager(new LinearLayoutManager(getContext()));
        recyclerHistory.setAdapter(new HistoryAdapter(data.getHistory()));
    }

    // --- CHART LOGIC (Copiada de DriverDetailFragment) ---
    private void setupCharts(List<DriverHistory> history) {
        if (history == null || history.isEmpty()) return;

        LineChart chartPos = getView().findViewById(R.id.chartRacePosition);
        LineChart chartPoints = getView().findViewById(R.id.chartPoints);

        List<Entry> entriesPos = new ArrayList<>();
        List<Entry> entriesPoints = new ArrayList<>();
        Map<Integer, Integer> maxPointsPerRound = new TreeMap<>();

        for (int i = 0; i < history.size(); i++) {
            DriverHistory h = history.get(i);
            if (h.getRacePos() > 0) {
                entriesPos.add(new Entry(i + 1, h.getRacePos()));
            }
            maxPointsPerRound.put(h.getRoundNumber(), h.getTotalPoints());
        }

        for (Map.Entry<Integer, Integer> entry : maxPointsPerRound.entrySet()) {
            entriesPoints.add(new Entry(entry.getKey(), entry.getValue()));
        }

        configureChartDesign(chartPos, true);
        LineDataSet setPos = new LineDataSet(entriesPos, "Pos");
        setPos.setColor(Color.parseColor("#D32F2F"));
        setPos.setCircleColor(Color.parseColor("#D32F2F"));
        setPos.setLineWidth(2f);
        setPos.setCircleRadius(4f);
        setPos.setDrawValues(false);
        setPos.setMode(LineDataSet.Mode.CUBIC_BEZIER);
        setPos.setDrawCircleHole(false);
        chartPos.setData(new LineData(setPos));
        chartPos.invalidate();

        configureChartDesign(chartPoints, false);
        LineDataSet setPoints = new LineDataSet(entriesPoints, "Pts");
        setPoints.setColor(Color.parseColor("#FFD700"));
        setPoints.setCircleColor(Color.parseColor("#FFD700"));
        setPoints.setLineWidth(2f);
        setPoints.setCircleRadius(4f);
        setPoints.setDrawValues(false);
        setPoints.setDrawFilled(true);
        setPoints.setFillColor(Color.parseColor("#FFD700"));
        setPoints.setFillAlpha(30);
        setPoints.setDrawCircleHole(false);
        chartPoints.setData(new LineData(setPoints));
        chartPoints.invalidate();
    }

    private void configureChartDesign(LineChart chart, boolean invertedY) {
        chart.setDescription(null);
        chart.getLegend().setEnabled(false);
        chart.setTouchEnabled(true);
        chart.setDragEnabled(true);
        chart.setScaleEnabled(false);
        chart.setPinchZoom(false);
        chart.setExtraOffsets(10f, 10f, 10f, 10f);

        XAxis xAxis = chart.getXAxis();
        xAxis.setPosition(XAxis.XAxisPosition.BOTTOM);
        xAxis.setTextColor(Color.parseColor("#999999"));
        xAxis.setDrawGridLines(false);
        xAxis.setDrawAxisLine(false);
        xAxis.setGranularity(1f);

        com.github.mikephil.charting.components.YAxis leftAxis = chart.getAxisLeft();
        leftAxis.setTextColor(Color.parseColor("#999999"));
        leftAxis.setDrawAxisLine(false);
        leftAxis.setDrawGridLines(true);
        leftAxis.setGridColor(Color.parseColor("#15FFFFFF"));
        leftAxis.setSpaceTop(15f);
        leftAxis.setSpaceBottom(15f);

        if (invertedY) {
            leftAxis.setInverted(true);
            leftAxis.setAxisMinimum(0.5f);
            leftAxis.resetAxisMaximum();
        } else {
            leftAxis.setAxisMinimum(0f);
        }
        chart.getAxisRight().setEnabled(false);
    }

    private void setStat(View view, String label, String value) {
        if (view == null) return;
        ((TextView)view.findViewById(R.id.tvStatLabel)).setText(label);
        ((TextView)view.findViewById(R.id.tvStatValue)).setText(value);
    }

    private void showEditBioDialog() {
        AlertDialog.Builder builder = new AlertDialog.Builder(requireContext());
        builder.setTitle("Edit Bio");
        final EditText input = new EditText(requireContext());
        input.setText(tvBio.getText().toString());
        input.setLines(5);
        input.setGravity(android.view.Gravity.TOP);
        builder.setView(input);
        builder.setPositiveButton("Save", (dialog, which) -> saveBio(input.getText().toString()));
        builder.setNegativeButton("Cancel", (dialog, which) -> dialog.cancel());
        builder.show();
    }

    private void saveBio(String newBio) {
        // Implementar lógica de guardado
        tvBio.setText(newBio);
    }

    @Override
    public void onResume() {
        super.onResume();
        // Cargar datos cada vez que la pantalla se muestra (incluso al volver de editar)
        SessionManager session = new SessionManager(requireContext());
        loadMyData(session.getUserId());
    }
}