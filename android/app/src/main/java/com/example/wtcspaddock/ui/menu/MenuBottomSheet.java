package com.example.wtcspaddock.ui;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;

public class MenuBottomSheet extends BottomSheetDialogFragment {

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_menu_sheet, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Obtenemos referencia al MainActivity para poder llamar a sus métodos
        MainActivity main = (MainActivity) getActivity();
        if (main == null) return;

        // 1. DASHBOARD (HOME) - ¡IMPORTANTE!
        view.findViewById(R.id.btnNavHome).setOnClickListener(v -> {
            main.navigateToHome(); // Llama al método que carga HomeFragment
            dismiss();
        });

        // 2. CALENDAR
        view.findViewById(R.id.btnNavCalendar).setOnClickListener(v -> {
            main.navigateToCalendar();
            dismiss();
        });

        // 3. STANDINGS
        view.findViewById(R.id.btnNavStandings).setOnClickListener(v -> {
            main.navigateToStandings();
            dismiss();
        });

        // 4. DRIVERS
        view.findViewById(R.id.btnNavDrivers).setOnClickListener(v -> {
            main.navigateToDrivers();
            dismiss();
        });

        // 5. TEAMS
        view.findViewById(R.id.btnNavTeams).setOnClickListener(v -> {
            main.navigateToTeams();
            dismiss();
        });

        // 6. NEWS
        view.findViewById(R.id.btnNavNews).setOnClickListener(v -> {
            main.navigateToNews();
            dismiss();
        });
    }
}