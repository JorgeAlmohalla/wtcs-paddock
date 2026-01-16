package com.example.wtcspaddock;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import com.example.wtcspaddock.ui.login.LoginActivity;
import com.example.wtcspaddock.utils.SessionManager;

public class SplashActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // Al usar el tema 'Theme.WTCSPaddock.Splash', el logo ya se ve antes de que empiece este código.
        super.onCreate(savedInstanceState);

        // NO usamos setContentView. No hace falta cargar un layout XML pesado.
        // El usuario ya está viendo el logo gracias al theme.

        // Lógica de redirección inmediata
        SessionManager session = new SessionManager(this);
        Intent intent;

        if (session.isLoggedIn()) {
            intent = new Intent(this, MainActivity.class);
        } else {
            intent = new Intent(this, LoginActivity.class);
        }

        startActivity(intent);
        finish(); // Cerramos esta actividad para que no se pueda volver atrás
    }
}