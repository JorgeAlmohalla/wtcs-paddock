package com.example.wtcspaddock.ui.drivers;

import android.graphics.Color;
import android.os.Bundle;
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
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.DriverDetailResponse;
import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;
import com.github.mikephil.charting.formatter.ValueFormatter;
import com.example.wtcspaddock.models.DriverHistory;
import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DriverDetailFragment extends Fragment {

    private static final String ARG_DRIVER_ID = "driver_id";
    private int driverId;

    // Vistas
    private TextView tvName, tvTeam, tvInput;
    private ImageView imgAvatar;

    // Stats Views (Incluidos via <include> en el XML)
    private View statStarts, statWins, statPoints;

    public static DriverDetailFragment newInstance(int driverId) {
        DriverDetailFragment fragment = new DriverDetailFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_DRIVER_ID, driverId);
        fragment.setArguments(args);
        return fragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_driver_detail, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        if (getArguments() != null) {
            driverId = getArguments().getInt(ARG_DRIVER_ID);
        }

        // 1. Vincular Vistas
        tvName = view.findViewById(R.id.tvProfileName);
        tvTeam = view.findViewById(R.id.tvProfileTeam);
        tvInput = view.findViewById(R.id.tvProfileInput);
        imgAvatar = view.findViewById(R.id.imgProfileAvatar);

        // Vincular cajitas de estadísticas
        statStarts = view.findViewById(R.id.statStarts);
        statWins = view.findViewById(R.id.statWins);
        statPoints = view.findViewById(R.id.statPoints);

        // 2. Cargar Datos
        loadDriverData(driverId);

        // 3. Configurar Charts (Vacíos o Dummy por ahora)
        // ... (Tu código de setupChart que ya tenías) ...
    }

    private void loadDriverData(int id) {
        RetrofitClient.getApiService().getDriverDetails(id).enqueue(new Callback<DriverDetailResponse>() {
            @Override
            public void onResponse(Call<DriverDetailResponse> call, Response<DriverDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    updateUI(response.body());
                } else {
                    Toast.makeText(getContext(), "Error loading profile", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<DriverDetailResponse> call, Throwable t) {
                Log.e("API", "Error driver detail: " + t.getMessage());
            }
        });
    }

    private void updateUI(DriverDetailResponse data) {
        DriverDetailResponse.DriverInfo info = data.getDriver();
        DriverDetailResponse.DriverStats stats = data.getStats();

        // --- CABECERA ---
        String flag = getFlagEmoji(info.getNationality());
        tvName.setText(info.getName() + " " + flag);
        tvTeam.setText(info.getTeam());

        // Equipment (Wheel/Pad)
        if (info.getEquipment() != null) {
            String equip = info.getEquipment().substring(0, 1).toUpperCase() + info.getEquipment().substring(1);
            tvInput.setText(equip);
            // Icono opcional según sea wheel o pad
        }

        // CONFIGURAR TABLA DE HISTORIAL
        androidx.recyclerview.widget.RecyclerView recyclerHistory = getView().findViewById(R.id.recyclerHistory);
        if (recyclerHistory != null) {
            recyclerHistory.setLayoutManager(new androidx.recyclerview.widget.LinearLayoutManager(getContext()));

            // Pasamos la lista completa, el adaptador la filtra solo
            HistoryAdapter adapter = new HistoryAdapter(data.getHistory());
            recyclerHistory.setAdapter(adapter);
        }

        setupCharts(data.getHistory());

        // Color del equipo en el texto del equipo (opcional) o borde
        try {
            tvTeam.setTextColor(Color.parseColor(info.getTeamColor()));
        } catch (Exception e) {
        }

        // Avatar
        if (info.getAvatar() != null) {
            Glide.with(this)
                    .load(info.getAvatar())
                    .apply(RequestOptions.circleCropTransform())
                    .placeholder(R.drawable.ic_profile)
                    .into(imgAvatar);
        }

        // --- ESTADÍSTICAS (BIG NUMBERS) ---
        setStat(statStarts, "STARTS", String.valueOf(stats.getStarts()));
        setStat(statWins, "WINS", String.valueOf(stats.getWins()));
        setStat(statPoints, "POINTS", String.valueOf(stats.getPoints()));
    }

    // Método helper para rellenar las cajitas <include>
    private void setStat(View statView, String label, String value) {
        if (statView == null) return;
        TextView tvVal = statView.findViewById(R.id.tvStatValue);
        TextView tvLbl = statView.findViewById(R.id.tvStatLabel);

        tvVal.setText(value);
        tvLbl.setText(label);
    }

    // Helper Bandera
    private String getFlagEmoji(String countryCode) {
        if (countryCode == null) return "";
        try {
            int firstLetter = Character.codePointAt(countryCode, 0) - 0x41 + 0x1F1E6;
            int secondLetter = Character.codePointAt(countryCode, 1) - 0x41 + 0x1F1E6;
            return new String(Character.toChars(firstLetter)) + new String(Character.toChars(secondLetter));
        } catch (Exception e) {
            return "";
        }
    }

    private void setupCharts(List<DriverHistory> history) {
        if (history == null || history.isEmpty()) return;

        LineChart chartPos = getView().findViewById(R.id.chartRacePosition);
        LineChart chartPoints = getView().findViewById(R.id.chartPoints);

        List<Entry> entriesPos = new ArrayList<>();
        List<Entry> entriesPoints = new ArrayList<>();

        // 1. Filtrar para puntos (Último valor por ronda)
        java.util.Map<Integer, Integer> maxPointsPerRound = new java.util.TreeMap<>();

        // 2. Llenar datos
        for (int i = 0; i < history.size(); i++) {
            DriverHistory h = history.get(i);

            // Posición: Usamos índice secuencial para ver Sprint y Feature por separado
            if (h.getRacePos() > 0) {
                entriesPos.add(new Entry(i + 1, h.getRacePos()));
            }

            // Puntos: Guardamos para filtrar luego
            maxPointsPerRound.put(h.getRoundNumber(), h.getTotalPoints());
        }

        // Puntos filtrados (solo 1 por ronda)
        for (java.util.Map.Entry<Integer, Integer> entry : maxPointsPerRound.entrySet()) {
            entriesPoints.add(new Entry(entry.getKey(), entry.getValue()));
        }

        // --- DISEÑO GRÁFICO POSICIÓN (ROJO) ---
        configureChartDesign(chartPos, true); // Invertido

        LineDataSet setPos = new LineDataSet(entriesPos, "Race Position");
        setPos.setColor(Color.parseColor("#D32F2F")); // ROJO WTCS
        setPos.setCircleColor(Color.parseColor("#D32F2F"));
        setPos.setLineWidth(2f);
        setPos.setCircleRadius(4f);
        setPos.setDrawValues(false); // <--- QUITAR NÚMEROS
        setPos.setMode(LineDataSet.Mode.CUBIC_BEZIER); // Línea curva suave
        setPos.setDrawCircleHole(false);

        chartPos.setData(new LineData(setPos));
        chartPos.invalidate();

        // --- DISEÑO GRÁFICO PUNTOS (DORADO) ---
        configureChartDesign(chartPoints, false); // Normal

        LineDataSet setPoints = new LineDataSet(entriesPoints, "Championship Points");
        setPoints.setColor(Color.parseColor("#FFD700")); // DORADO
        setPoints.setCircleColor(Color.parseColor("#FFD700"));
        setPoints.setLineWidth(2f);
        setPoints.setCircleRadius(4f);
        setPoints.setDrawValues(false); // <--- QUITAR NÚMEROS
        setPoints.setDrawFilled(true);
        setPoints.setFillColor(Color.parseColor("#FFD700"));
        setPoints.setFillAlpha(30); // Transparencia suave
        setPoints.setDrawCircleHole(false);

        chartPoints.setData(new LineData(setPoints));
        chartPoints.invalidate();
    }

    // Método para poner el gráfico bonito (Estilo Dark)
    private void configureChartDesign(LineChart chart, boolean invertedY) {
        // 1. Configuración General
        chart.setDescription(null);
        chart.getLegend().setEnabled(false); // Sin leyenda
        chart.setTouchEnabled(true);
        chart.setDragEnabled(true);
        chart.setScaleEnabled(false);
        chart.setPinchZoom(false);

        // Margen extra alrededor para que no se corten los puntos gordos
        chart.setExtraOffsets(10f, 10f, 10f, 10f);

        // 2. EJE X (Abajo) - LIMPIEZA TOTAL
        com.github.mikephil.charting.components.XAxis xAxis = chart.getXAxis();
        xAxis.setPosition(com.github.mikephil.charting.components.XAxis.XAxisPosition.BOTTOM);
        xAxis.setTextColor(Color.parseColor("#999999")); // Gris medio
        xAxis.setDrawGridLines(false); // <--- ESTO QUITA LAS LÍNEAS VERTICALES
        xAxis.setDrawAxisLine(false);  // Quita la línea base del eje
        xAxis.setGranularity(1f);

        // 3. EJE Y (Izquierda) - SUTIL
        com.github.mikephil.charting.components.YAxis leftAxis = chart.getAxisLeft();
        leftAxis.setTextColor(Color.parseColor("#999999"));
        leftAxis.setDrawAxisLine(false); // Quita la línea vertical del eje

        // Rejilla horizontal MUY sutil (apenas visible)
        leftAxis.setDrawGridLines(true);
        leftAxis.setGridColor(Color.parseColor("#15FFFFFF")); // 15% de opacidad (casi invisible)
        // O pon setDrawGridLines(false) si no quieres NINGUNA línea.

        // Espaciado para que la línea no toque los bordes
        leftAxis.setSpaceTop(15f);
        leftAxis.setSpaceBottom(15f);

        if (invertedY) {
            leftAxis.setInverted(true);
            leftAxis.setAxisMinimum(-1f); // Truco para el 1er puesto
            leftAxis.resetAxisMaximum();
        } else {
            leftAxis.setAxisMinimum(0f);
        }

        // 4. EJE Y (Derecha) - ¡FUERA!
        chart.getAxisRight().setEnabled(false); // <--- ESTO QUITA LOS NÚMEROS NEGROS
    }
}