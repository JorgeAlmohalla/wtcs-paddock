package com.example.wtcspaddock.ui.profile;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.User;
import com.example.wtcspaddock.utils.SessionManager;
import com.google.android.material.imageview.ShapeableImageView;
import com.google.android.material.textfield.TextInputEditText;

import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class EditProfileFragment extends Fragment {

    private TextInputEditText etName, etEmail, etSteam, etNation, etNumber, etBio;
    private Spinner spinnerInput;
    private ShapeableImageView imgAvatar;

    // Opciones del Spinner
    private String[] inputMethods = {"Wheel", "Gamepad", "Keyboard"};

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_edit_profile, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // 1. Vincular Vistas
        etName = view.findViewById(R.id.etName);
        etEmail = view.findViewById(R.id.etEmail);
        etSteam = view.findViewById(R.id.etSteamId);
        etNation = view.findViewById(R.id.etNationality);
        etNumber = view.findViewById(R.id.etNumber);
        etBio = view.findViewById(R.id.etBio);
        spinnerInput = view.findViewById(R.id.spinnerInput);
        imgAvatar = view.findViewById(R.id.imgEditAvatar);

        // 2. Configurar Spinner
        ArrayAdapter<String> adapter = new ArrayAdapter<>(requireContext(), android.R.layout.simple_spinner_dropdown_item, inputMethods);
        spinnerInput.setAdapter(adapter);

        // 3. Cargar datos actuales
        loadCurrentData();

        // 4. Botón Guardar
        view.findViewById(R.id.btnSaveProfile).setOnClickListener(v -> saveProfile());

        // 5. Botón Change Photo (Placeholder)
        view.findViewById(R.id.btnChangePhoto).setOnClickListener(v ->
                Toast.makeText(getContext(), "Photo upload coming soon", Toast.LENGTH_SHORT).show()
        );
    }

    private void loadCurrentData() {
        SessionManager session = new SessionManager(requireContext());
        String token = "Bearer " + session.getToken();

        // Llamamos a /user para obtener todos los campos (email, steam, etc.)
        RetrofitClient.getApiService().getUserProfile(token).enqueue(new Callback<User>() {
            @Override
            public void onResponse(Call<User> call, Response<User> response) {
                if (response.isSuccessful() && response.body() != null) {
                    User user = response.body();

                    // Rellenar campos de texto
                    // Usamos setText con cadena vacía por si el dato viene null
                    etName.setText(user.getName());
                    etEmail.setText(user.getEmail());
                    etSteam.setText(user.getSteamId() != null ? user.getSteamId() : "");
                    etNation.setText(user.getNationality() != null ? user.getNationality() : "");
                    etNumber.setText(user.getDriverNumber() != null ? user.getDriverNumber() : "");
                    etBio.setText(user.getBio() != null ? user.getBio() : "");

                    // Seleccionar Spinner
                    selectSpinnerItem(user.getEquipment());

                    // Cargar Foto
                    if (user.getAvatarUrl() != null) {
                        Glide.with(EditProfileFragment.this)
                                .load(user.getAvatarUrl())
                                .apply(RequestOptions.circleCropTransform())
                                .into(imgAvatar);
                    }
                } else {
                    Toast.makeText(getContext(), "Error loading user data", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<User> call, Throwable t) {
                Toast.makeText(getContext(), "Network error", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void saveProfile() {
        // 1. Obtener valores de los inputs
        String nameStr = etName.getText().toString().trim();
        String emailStr = etEmail.getText().toString().trim();
        String steamStr = etSteam.getText().toString().trim();
        String nationStr = etNation.getText().toString().trim();
        String numStr = etNumber.getText().toString().trim();
        String bioStr = etBio.getText().toString().trim();

        // Obtener valor del spinner y pasarlo a minúsculas ("Wheel" -> "wheel")
        String equipmentStr = spinnerInput.getSelectedItem().toString().toLowerCase();

        // 2. Convertir a RequestBody (Necesario para @Multipart)
        RequestBody name = createPart(nameStr);
        RequestBody email = createPart(emailStr);
        RequestBody steam = createPart(steamStr);
        RequestBody nation = createPart(nationStr);
        RequestBody number = createPart(numStr);
        RequestBody bio = createPart(bioStr);
        RequestBody equipment = createPart(equipmentStr);

        // 3. Preparar Token
        SessionManager session = new SessionManager(requireContext());
        String token = "Bearer " + session.getToken();

        // 4. Llamada a la API
        // Pasamos 'null' en el avatar porque aún no implementamos la selección de fichero
        RetrofitClient.getApiService().updateProfile(
                token,
                name,
                email,
                nation,
                steam,
                equipment,
                number,
                bio,
                null // Avatar null por ahora
        ).enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(getContext(), "Profile Saved Successfully!", Toast.LENGTH_SHORT).show();
                    // Volver atrás para ver los cambios reflejados
                    getParentFragmentManager().popBackStack();
                } else {
                    Toast.makeText(getContext(), "Error updating profile: " + response.code(), Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<Void> call, Throwable t) {
                Toast.makeText(getContext(), "Connection failed: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    // Método auxiliar para crear partes de texto
    private RequestBody createPart(String value) {
        if (value == null) value = "";
        return RequestBody.create(MediaType.parse("text/plain"), value);
    }

    private void selectSpinnerItem(String equipment) {
        if (equipment == null) return;
        for (int i = 0; i < inputMethods.length; i++) {
            if (inputMethods[i].equalsIgnoreCase(equipment)) {
                spinnerInput.setSelection(i);
                break;
            }
        }
    }
}