package com.example.wtcspaddock.ui.calendar;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.fragment.app.Fragment;
import com.example.wtcspaddock.R;

public class CalendarFragment extends Fragment {
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // Aquí cargamos el diseño complejo
        return inflater.inflate(R.layout.fragment_calendar, container, false);
    }
}