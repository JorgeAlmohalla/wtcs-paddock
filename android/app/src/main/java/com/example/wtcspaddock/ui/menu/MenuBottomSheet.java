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

        // Referencia al Activity principal para poder navegar
        MainActivity mainActivity = (MainActivity) getActivity();

        // 1. CALENDAR
        view.findViewById(R.id.btnNavCalendar).setOnClickListener(v -> {
            if (mainActivity != null) mainActivity.navigateToCalendar();
            dismiss();
        });

        // 2. STANDINGS
        view.findViewById(R.id.btnNavStandings).setOnClickListener(v -> {
            if (mainActivity != null) mainActivity.navigateToStandings();
            dismiss();
        });

        // 3. Otros botones (Drivers, Teams...) - Solo Toast por ahora
        view.findViewById(R.id.btnNavDrivers).setOnClickListener(v -> {
            Toast.makeText(getContext(), "Drivers Section - WIP", Toast.LENGTH_SHORT).show();
            dismiss();
        });

        // ... Repite para Teams y News ...
    }
}