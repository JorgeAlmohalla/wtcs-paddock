package com.example.wtcspaddock.ui.profile;

import android.net.Uri;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.User;
import com.example.wtcspaddock.utils.FileUtils; // Asegúrate de importar esto
import com.example.wtcspaddock.utils.SessionManager;
import com.google.android.material.imageview.ShapeableImageView;
import com.google.android.material.textfield.TextInputEditText;

import java.io.File;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class EditProfileFragment extends Fragment {

    private TextInputEditText etName, etEmail, etSteam, etNation, etNumber, etBio;
    private Spinner spinnerInput;
    private ShapeableImageView imgAvatar;

    private Uri selectedImageUri = null;
    private String[] inputMethods = {"Wheel", "Gamepad", "Keyboard"};

    // 1. LANZADOR DE GALERÍA
    private final ActivityResultLauncher<String> pickImage = registerForActivityResult(
            new ActivityResultContracts.GetContent(),
            uri -> {
                if (uri != null) {
                    selectedImageUri = uri;
                    // Mostrar la foto seleccionada inmediatamente
                    Glide.with(this).load(uri).apply(RequestOptions.circleCropTransform()).into(imgAvatar);
                }
            }
    );

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_edit_profile, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        etName = view.findViewById(R.id.etName);
        etEmail = view.findViewById(R.id.etEmail);
        etSteam = view.findViewById(R.id.etSteamId);
        etNation = view.findViewById(R.id.etNationality);
        etNumber = view.findViewById(R.id.etNumber);
        etBio = view.findViewById(R.id.etBio);
        spinnerInput = view.findViewById(R.id.spinnerInput);
        imgAvatar = view.findViewById(R.id.imgEditAvatar);

        ArrayAdapter<String> adapter = new ArrayAdapter<>(requireContext(), android.R.layout.simple_spinner_dropdown_item, inputMethods);
        spinnerInput.setAdapter(adapter);

        // Cargar datos iniciales
        loadCurrentData();

        // --- BOTONES CONECTADOS ---

        // Guardar Perfil
        view.findViewById(R.id.btnSaveProfile).setOnClickListener(v -> saveProfile());

        // Cambiar Foto (AHORA SÍ ABRE LA GALERÍA)
        view.findViewById(R.id.btnChangePhoto).setOnClickListener(v -> pickImage.launch("image/*"));

        // Cambiar Password (AHORA SÍ ABRE EL DIÁLOGO)
        view.findViewById(R.id.btnChangePassword).setOnClickListener(v -> showChangePasswordDialog());
    }

    private void loadCurrentData() {
        SessionManager session = new SessionManager(requireContext());
        RetrofitClient.getApiService().getUserProfile("Bearer " + session.getToken()).enqueue(new Callback<User>() {
            @Override
            public void onResponse(Call<User> call, Response<User> response) {
                if (response.isSuccessful() && response.body() != null) {
                    User user = response.body();
                    etName.setText(user.getName());
                    etEmail.setText(user.getEmail());

                    // Usar cadenas vacías si es null para evitar crash
                    etSteam.setText(user.getSteamId() != null ? user.getSteamId() : "");
                    etNation.setText(user.getNationality() != null ? user.getNationality() : "");
                    etNumber.setText(user.getDriverNumber() != null ? user.getDriverNumber() : "");
                    etBio.setText(user.getBio() != null ? user.getBio() : "");

                    selectSpinnerItem(user.getEquipment());

                    // Cargar Avatar actual (Si no carga, revisa models/User.java y el parche de IP)
                    if (user.getAvatarUrl() != null) {
                        Glide.with(EditProfileFragment.this)
                                .load(user.getAvatarUrl())
                                .apply(RequestOptions.circleCropTransform())
                                .into(imgAvatar);
                    }
                }
            }
            @Override public void onFailure(Call<User> call, Throwable t) {}
        });
    }

    private void saveProfile() {
        // 1. Textos
        RequestBody name = createPart(etName.getText().toString());
        RequestBody email = createPart(etEmail.getText().toString());
        RequestBody nation = createPart(etNation.getText().toString());
        RequestBody steam = createPart(etSteam.getText().toString());
        RequestBody number = createPart(etNumber.getText().toString());
        RequestBody bio = createPart(etBio.getText().toString());
        RequestBody equipment = createPart(spinnerInput.getSelectedItem().toString().toLowerCase());

        // 2. Imagen (Multipart)
        MultipartBody.Part avatarPart = null;
        if (selectedImageUri != null) {
            File file = FileUtils.getFileFromUri(requireContext(), selectedImageUri);
            if (file != null) {
                // "avatar" es el nombre del campo que espera Laravel
                RequestBody reqFile = RequestBody.create(MediaType.parse("image/*"), file);
                avatarPart = MultipartBody.Part.createFormData("avatar", file.getName(), reqFile);
            }
        }

        SessionManager session = new SessionManager(requireContext());
        String token = "Bearer " + session.getToken();

        RetrofitClient.getApiService().updateProfile(
                token, name, email, nation, steam, equipment, number, bio, avatarPart
        ).enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(getContext(), "Saved!", Toast.LENGTH_SHORT).show();
                    // Actualizar avatar en SessionManager para el menú
                    if (selectedImageUri != null) {
                        // Un pequeño hack para ver el cambio en el menú sin recargar de la red
                        session.saveUserAvatar(selectedImageUri.toString());
                    }
                    getParentFragmentManager().popBackStack();
                } else {
                    Toast.makeText(getContext(), "Error: " + response.code(), Toast.LENGTH_SHORT).show();
                }
            }
            @Override public void onFailure(Call<Void> call, Throwable t) {
                Toast.makeText(getContext(), "Network error", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void showChangePasswordDialog() {
        // 1. Crear el Builder
        android.app.AlertDialog.Builder builder = new android.app.AlertDialog.Builder(requireContext());

        // 2. Inflar el diseño personalizado
        View dialogView = getLayoutInflater().inflate(R.layout.dialog_change_password, null);
        builder.setView(dialogView);

        // 3. Crear el diálogo (pero no mostrarlo aún)
        android.app.AlertDialog dialog = builder.create();

        // Hacer el fondo del diálogo transparente para que se vean las esquinas redondeadas de nuestra tarjeta
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));
        }

        // 4. Vincular Vistas del diseño personalizado
        TextInputEditText etCurrent = dialogView.findViewById(R.id.etDialogCurrent);
        TextInputEditText etNew = dialogView.findViewById(R.id.etDialogNew);
        TextInputEditText etConfirm = dialogView.findViewById(R.id.etDialogConfirm);
        View btnCancel = dialogView.findViewById(R.id.btnDialogCancel);
        View btnUpdate = dialogView.findViewById(R.id.btnDialogUpdate);

        // 5. Botón Cancelar
        btnCancel.setOnClickListener(v -> dialog.dismiss());

        // 6. Botón Actualizar
        btnUpdate.setOnClickListener(v -> {
            String c = etCurrent.getText().toString();
            String n = etNew.getText().toString();
            String cf = etConfirm.getText().toString();

            if (n.isEmpty() || c.isEmpty()) {
                Toast.makeText(getContext(), "Fields cannot be empty", Toast.LENGTH_SHORT).show();
                return;
            }
            if (!n.equals(cf)) {
                Toast.makeText(getContext(), "Passwords do not match", Toast.LENGTH_SHORT).show();
                return;
            }

            // Llamar a la API
            performPasswordChange(c, n, cf);
            dialog.dismiss();
        });

        dialog.show();
    }

    private void performPasswordChange(String current, String newVal, String confirm) {
        SessionManager session = new SessionManager(requireContext());
        RetrofitClient.getApiService().changePassword("Bearer " + session.getToken(), current, newVal, confirm)
                .enqueue(new Callback<Void>() {
                    @Override
                    public void onResponse(Call<Void> call, Response<Void> response) {
                        if (response.isSuccessful()) Toast.makeText(getContext(), "Password Changed", Toast.LENGTH_SHORT).show();
                        else Toast.makeText(getContext(), "Error: Check current password", Toast.LENGTH_SHORT).show();
                    }
                    @Override public void onFailure(Call<Void> call, Throwable t) {}
                });
    }

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