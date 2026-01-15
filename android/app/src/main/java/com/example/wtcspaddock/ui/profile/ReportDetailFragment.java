package com.example.wtcspaddock.ui.profile;

import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.Report;

public class ReportDetailFragment extends Fragment {

    private static final String ARG_REPORT = "report_obj";
    private Report report;

    public static ReportDetailFragment newInstance(Report report) {
        ReportDetailFragment f = new ReportDetailFragment();
        Bundle args = new Bundle();
        args.putSerializable(ARG_REPORT, report);
        f.setArguments(args);
        return f;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_report_detail, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // 1. Recuperar el objeto Report
        if (getArguments() != null) {
            report = (Report) getArguments().getSerializable(ARG_REPORT);
        }

        // 2. Comprobar si es nulo ANTES de usarlo
        if (report != null) {
            // Vincular y rellenar vistas
            TextView status = view.findViewById(R.id.tvDetailStatus);
            TextView decision = view.findViewById(R.id.tvDetailDecision);
            TextView notes = view.findViewById(R.id.tvDetailNotes); // <--- El nuevo campo

            status.setText(report.getStatus());

            // Colores del estado
            if ("RESOLVED".equalsIgnoreCase(report.getStatus())) {
                status.setBackgroundColor(Color.parseColor("#D32F2F")); // Rojo
            } else {
                status.setBackgroundColor(Color.parseColor("#FFA000")); // Naranja
            }

            ((TextView)view.findViewById(R.id.tvDetailRace)).setText(report.getRaceName());
            ((TextView)view.findViewById(R.id.tvDetailLap)).setText(report.getLap());
            ((TextView)view.findViewById(R.id.tvDetailDescription)).setText(report.getDescription());
            ((TextView)view.findViewById(R.id.tvDetailVideo)).setText(report.getVideoUrl());

            // Decisión (DSQ, +5s, etc.)
            decision.setText(report.getDecision());

            // Notas del Comisario (Explicación)
            if (report.getStewardNotes() != null && !report.getStewardNotes().isEmpty()) {
                notes.setText(report.getStewardNotes());
                notes.setVisibility(View.VISIBLE);
            } else {
                notes.setVisibility(View.GONE);
            }

        } else {
            // Si el reporte es nulo, mostramos error y salimos para no dejar la pantalla en blanco
            Toast.makeText(getContext(), "Error: Report not found", Toast.LENGTH_SHORT).show();
            getParentFragmentManager().popBackStack();
        }
    }
}