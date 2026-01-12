package com.example.wtcspaddock.ui.calendar;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.ResultRow;

import java.util.ArrayList;
import java.util.List;

public class RaceSessionFragment extends Fragment {

    private static final String ARG_SESSION_TYPE = "SESSION_TYPE";
    private String sessionType; // "qualy", "sprint", "feature"
    private RecyclerView recyclerView;
    private TextView tvEmpty; // Opcional: por si quieres mostrar "Cargando..."

    // Método estático para crear el fragmento con argumentos
    public static RaceSessionFragment newInstance(String sessionType) {
        RaceSessionFragment fragment = new RaceSessionFragment();
        Bundle args = new Bundle();
        args.putString(ARG_SESSION_TYPE, sessionType);
        fragment.setArguments(args);
        return fragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Reutilizamos el layout de lista que ya tienes
        return inflater.inflate(R.layout.fragment_calendar_list, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // 1. Recuperar argumentos
        if (getArguments() != null) {
            sessionType = getArguments().getString(ARG_SESSION_TYPE);
        } else {
            sessionType = "qualy"; // Valor por defecto
        }

        // 2. Configurar RecyclerView
        recyclerView = view.findViewById(R.id.recyclerCalendar);
        recyclerView.setLayoutManager(new LinearLayoutManager(getContext()));

        // 3. Intentar cargar datos inmediatamente
        loadDataFromActivity();
    }

    @Override
    public void onResume() {
        super.onResume();
        // 4. Recargar datos cada vez que el fragmento se hace visible
        loadDataFromActivity();
    }

    // --- LÓGICA DE CARGA ---
    // Este método le pide a la Actividad padre los datos descargados de la API
    public void loadDataFromActivity() {
        // CAMBIO: Ahora buscamos el fragmento padre (getParentFragment), no la Activity
        Fragment parent = getParentFragment();

        if (parent instanceof RaceDetailFragment) {
            RaceDetailFragment detailFragment = (RaceDetailFragment) parent;

            List<ResultRow> results = detailFragment.getSessionData(sessionType);

            if (results != null && !results.isEmpty()) {
                ResultsAdapter adapter = new ResultsAdapter(results, sessionType);
                RecyclerView rv = getView().findViewById(R.id.recyclerCalendar);
                rv.setAdapter(adapter);
            }
        }
    }
}