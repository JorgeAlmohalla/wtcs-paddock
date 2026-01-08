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
        MainActivity main = (MainActivity) getActivity();
        if (main == null) return;

        // YA NO EXISTE EL btnNavHome PORQUE ESTÁ EN LA BARRA PRINCIPAL

        // 1. CALENDAR
        view.findViewById(R.id.btnNavCalendar).setOnClickListener(v -> {
            main.navigateToCalendar();
            dismiss();
        });

        // ... (Standings, Drivers, Teams, News igual que antes) ...
        view.findViewById(R.id.btnNavStandings).setOnClickListener(v -> { main.navigateToStandings(); dismiss(); });
        view.findViewById(R.id.btnNavDrivers).setOnClickListener(v -> { main.navigateToDrivers(); dismiss(); });
        view.findViewById(R.id.btnNavTeams).setOnClickListener(v -> { main.navigateToTeams(); dismiss(); });
        view.findViewById(R.id.btnNavNews).setOnClickListener(v -> { main.navigateToNews(); dismiss(); });

        // 2. LOGOUT (El nuevo botón en la rejilla)
        view.findViewById(R.id.btnNavLogout).setOnClickListener(v -> {
            dismiss(); // Cerramos el menú primero
            main.performLogout(); // Llamamos al método de logout
        });
    }
}