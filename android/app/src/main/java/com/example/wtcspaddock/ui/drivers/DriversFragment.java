package com.example.wtcspaddock.ui.drivers;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.widget.SearchView;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.Driver;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DriversFragment extends Fragment {

    private RecyclerView recyclerView;
    private DriversAdapter adapter;
    private SearchView searchView;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Usamos el NUEVO layout con buscador
        return inflater.inflate(R.layout.fragment_drivers, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // 1. Configurar RecyclerView (Lineal = 1 columna)
        recyclerView = view.findViewById(R.id.recyclerDrivers);
        recyclerView.setLayoutManager(new LinearLayoutManager(getContext()));

        // 2. Configurar Buscador
        searchView = view.findViewById(R.id.searchView);
        setupSearch();

        // 3. Cargar Datos
        loadDrivers();
    }

    private void setupSearch() {
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                // Cada vez que escribes letra, filtramos
                if (adapter != null) {
                    adapter.filterList(newText);
                }
                return true;
            }
        });
    }

    private void loadDrivers() {
        RetrofitClient.getApiService().getDrivers().enqueue(new Callback<List<Driver>>() {
            @Override
            public void onResponse(Call<List<Driver>> call, Response<List<Driver>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    adapter = new DriversAdapter(getContext(), response.body());
                    recyclerView.setAdapter(adapter);
                }
            }

            @Override
            public void onFailure(Call<List<Driver>> call, Throwable t) {
                Log.e("API", "Error drivers: " + t.getMessage());
            }
        });
    }
}