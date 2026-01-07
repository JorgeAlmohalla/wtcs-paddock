package com.example.wtcspaddock.ui.login;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.api.RetrofitClient;
import com.example.wtcspaddock.models.LoginRequest;
import com.example.wtcspaddock.models.LoginResponse;
import com.example.wtcspaddock.utils.SessionManager;
import com.google.android.material.textfield.TextInputEditText;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {

    private TextInputEditText etEmail, etPassword;
    private Button btnLogin;
    private ProgressBar progressBar;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // 1. Inicializar SessionManager
        sessionManager = new SessionManager(this);

        // Si ya hay token, saltar directamente al Main
        if (sessionManager.isLoggedIn()) {
            goToMainActivity();
        }

        // 2. Vincular Vistas
        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnLogin);
        progressBar = findViewById(R.id.progressBar);

        // 3. Listener del Botón
        btnLogin.setOnClickListener(v -> performLogin());
    }

    private void performLogin() {
        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString().trim();

        if (email.isEmpty() || password.isEmpty()) {
            Toast.makeText(this, "Por favor rellena todos los campos", Toast.LENGTH_SHORT).show();
            return;
        }

        // Mostrar carga y bloquear botón
        progressBar.setVisibility(View.VISIBLE);
        btnLogin.setEnabled(false);

        // Crear objeto de petición
        LoginRequest request = new LoginRequest(email, password);

        // 4. Llamada a Retrofit
        RetrofitClient.getApiService().login(request).enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                progressBar.setVisibility(View.GONE);
                btnLogin.setEnabled(true);

                if (response.isSuccessful() && response.body() != null) {
                    // ÉXITO: Laravel devolvió 200 OK y el Token
                    String token = response.body().getToken();

                    // Guardar token
                    sessionManager.saveToken(token);

                    Toast.makeText(LoginActivity.this, "Bienvenido " + response.body().getUser().getName(), Toast.LENGTH_LONG).show();
                    goToMainActivity();

                } else {
                    // ERROR: Laravel devolvió 401 (Credenciales mal) o 500
                    Toast.makeText(LoginActivity.this, "Error: Credenciales incorrectas", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                btnLogin.setEnabled(true);
                // ERROR DE RED: No se ve el servidor
                Toast.makeText(LoginActivity.this, "Error de conexión: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }

    private void goToMainActivity() {
        Intent intent = new Intent(this, MainActivity.class);
        // Flags para que el usuario no pueda volver al login dando "Atrás"
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
        finish();
    }
}