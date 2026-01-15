package com.example.wtcspaddock.ui.profile;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.FormData;
import com.example.wtcspaddock.utils.SessionManager;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ReportIncidentFragment extends Fragment {

    private Spinner spinnerRaces, spinnerDrivers;
    private EditText etLap, etDescription, etVideo;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_report_incident, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        spinnerRaces = view.findViewById(R.id.spinnerRaces);
        spinnerDrivers = view.findViewById(R.id.spinnerDrivers);
        etLap = view.findViewById(R.id.etLap);
        etDescription = view.findViewById(R.id.etDescription);
        etVideo = view.findViewById(R.id.etVideo);

        // 1. Cargar datos para los Spinners
        loadFormData();

        // 2. Botón Enviar
        view.findViewById(R.id.btnSubmitReport).setOnClickListener(v -> submitReport());

        // 3. Botón Cancelar
        view.findViewById(R.id.btnCancelReport).setOnClickListener(v -> getParentFragmentManager().popBackStack());
    }

    private void loadFormData() {
        SessionManager session = new SessionManager(requireContext());
        String token = "Bearer " + session.getToken();

        RetrofitClient.getApiService().getFormData(token).enqueue(new Callback<FormData>() {
            @Override
            public void onResponse(Call<FormData> call, Response<FormData> response) {
                if (response.isSuccessful() && response.body() != null) {
                    FormData data = response.body();

                    // Llenar Spinner Races
                    ArrayAdapter<FormData.SimpleItem> adapterRaces = new ArrayAdapter<>(requireContext(),
                            android.R.layout.simple_spinner_dropdown_item, data.races);
                    spinnerRaces.setAdapter(adapterRaces);

                    // Llenar Spinner Drivers
                    ArrayAdapter<FormData.SimpleItem> adapterDrivers = new ArrayAdapter<>(requireContext(),
                            android.R.layout.simple_spinner_dropdown_item, data.drivers);
                    spinnerDrivers.setAdapter(adapterDrivers);
                }
            }
            @Override public void onFailure(Call<FormData> call, Throwable t) {}
        });
    }

    private void submitReport() {
        // Validar
        if (etLap.getText().toString().isEmpty() || etDescription.getText().toString().isEmpty()) {
            Toast.makeText(getContext(), "Please fill all fields", Toast.LENGTH_SHORT).show();
            return;
        }

        // Obtener IDs seleccionados
        FormData.SimpleItem selectedRace = (FormData.SimpleItem) spinnerRaces.getSelectedItem();
        FormData.SimpleItem selectedDriver = (FormData.SimpleItem) spinnerDrivers.getSelectedItem();

        if (selectedRace == null || selectedDriver == null) return;

        // Enviar
        SessionManager session = new SessionManager(requireContext());
        String token = "Bearer " + session.getToken();

        RetrofitClient.getApiService().submitReport(
                token,
                selectedRace.id,
                selectedDriver.id,
                etLap.getText().toString(), // <--- CAMBIO: Enviamos el String directo
                etDescription.getText().toString(),
                etVideo.getText().toString()
        ).enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(getContext(), "Report Submitted!", Toast.LENGTH_LONG).show();
                    getParentFragmentManager().popBackStack(); // Volver
                } else {
                    try {
                        String errorBody = response.errorBody().string();
                        android.util.Log.e("REPORT_ERROR", "Code: " + response.code() + " Body: " + errorBody);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }
            @Override public void onFailure(Call<Void> call, Throwable t) {
                Toast.makeText(getContext(), "Connection failed", Toast.LENGTH_SHORT).show();
            }
        });
    }
}