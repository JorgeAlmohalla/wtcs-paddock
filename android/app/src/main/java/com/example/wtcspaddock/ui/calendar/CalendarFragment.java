package com.example.wtcspaddock.ui.calendar;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.CalendarResponse;
import com.example.wtcspaddock.models.Race;
import com.example.wtcspaddock.models.RaceEvent;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class CalendarFragment extends Fragment {

    private RecyclerView recyclerView;
    private CalendarAdapter adapter;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Asegúrate de que este XML contenga un RecyclerView
        return inflater.inflate(R.layout.fragment_calendar_list, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // 1. Configurar RecyclerView
        recyclerView = view.findViewById(R.id.recyclerCalendar); // CREAREMOS ESTE ID AHORA
        recyclerView.setLayoutManager(new LinearLayoutManager(getContext()));

        // 2. Pedir datos
        loadCalendar();
    }

    private void loadCalendar() {
        RetrofitClient.getApiService().getCalendar().enqueue(new Callback<CalendarResponse>() {
            @Override
            public void onResponse(Call<CalendarResponse> call, Response<CalendarResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Race> rawRaces = response.body().getData();

                    // 1. AGRUPAR CARRERAS POR RONDA
                    List<RaceEvent> events = groupRacesByRound(rawRaces);

                    // 2. PASAR LOS EVENTOS AGRUPADOS AL ADAPTADOR
                    adapter = new CalendarAdapter(getContext(), events);
                    recyclerView.setAdapter(adapter);
                }
            }

            @Override
            public void onFailure(Call<CalendarResponse> call, Throwable t) {
                Log.e("API", "Error calendar: " + t.getMessage());
            }
        });
    }

    // --- EL ALGORITMO DE AGRUPACIÓN ---
    private List<RaceEvent> groupRacesByRound(List<Race> rawRaces) {
        // Usamos un Map para evitar duplicados. Clave = Número de Ronda.
        Map<Integer, RaceEvent> eventsMap = new LinkedHashMap<>();

        for (Race race : rawRaces) {
            int round = race.getRound();

            // Si esta ronda NO existe aún en el mapa, la creamos
            if (!eventsMap.containsKey(round)) {
                RaceEvent newEvent = new RaceEvent(
                        race.getRound(),
                        race.getTrackName(),
                        race.getDate(), // Usamos la fecha de la primera sesión que encontremos
                        race.getImageUrl()
                );
                eventsMap.put(round, newEvent);
            }

            // Añadimos la sesión (Sprint/Feature) a la lista interna del evento
            eventsMap.get(round).addSession(race);
        }

        // Convertimos los valores del mapa a una lista limpia
        return new ArrayList<>(eventsMap.values());
    }
}