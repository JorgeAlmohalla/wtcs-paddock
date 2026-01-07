package com.example.wtcspaddock;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

import com.example.wtcspaddock.ui.MenuBottomSheet;
import com.example.wtcspaddock.ui.calendar.CalendarFragment;
import com.example.wtcspaddock.ui.profile.ProfileFragment;
import com.example.wtcspaddock.ui.standings.StandingsFragment;
import com.example.wtcspaddock.ui.login.LoginActivity;
import com.example.wtcspaddock.utils.SessionManager;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.example.wtcspaddock.ui.home.HomeFragment;

public class MainActivity extends AppCompatActivity {

    private BottomNavigationView bottomNav;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        SessionManager session = new SessionManager(this);
        if (!session.isLoggedIn()) {
            startActivity(new Intent(this, LoginActivity.class));
            finish();
            return;
        }

        bottomNav = findViewById(R.id.bottom_navigation);

        // Listener de los 3 botones
        bottomNav.setOnItemSelectedListener(item -> {
            int itemId = item.getItemId();

            if (itemId == R.id.nav_profile) {
                // IZQUIERDA: Perfil
                loadFragment(new ProfileFragment());
                return true;

            } else if (itemId == R.id.nav_menu_hub) {
                // CENTRO: Abrir BottomSheet
                MenuBottomSheet bottomSheet = new MenuBottomSheet();
                bottomSheet.show(getSupportFragmentManager(), "WtcsMenu");
                return false; // False para que no se quede "seleccionado" visualmente

            } else if (itemId == R.id.nav_logout) {
                // DERECHA: Logout con confirmación
                showLogoutDialog();
                return false; // No queremos navegar, solo ejecutar la acción
            }
            return false;
        });

        // Cargar Calendario al inicio
        if (savedInstanceState == null) {
            loadFragment(new HomeFragment());
        }
    }

    private void loadFragment(Fragment fragment) {
        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .commit();
    }

    // --- MÉTODOS PÚBLICOS PARA EL BOTTOM SHEET ---

    public void navigateToCalendar() {
        loadFragment(new CalendarFragment());
        // Desmarcamos botones de la barra porque Calendar ya no está ahí
        bottomNav.getMenu().setGroupCheckable(0, true, false);
        for (int i=0; i<bottomNav.getMenu().size(); i++) {
            bottomNav.getMenu().getItem(i).setChecked(false);
        }
    }

    public void navigateToStandings() {
        loadFragment(new StandingsFragment());
        // Desmarcamos botones igual que arriba
        bottomNav.getMenu().setGroupCheckable(0, true, false);
        for (int i=0; i<bottomNav.getMenu().size(); i++) {
            bottomNav.getMenu().getItem(i).setChecked(false);
        }
    }

    public void navigateToHome() {
        loadFragment(new HomeFragment());

        if (bottomNav != null) {
            bottomNav.getMenu().setGroupCheckable(0, true, false);
            for (int i = 0; i < bottomNav.getMenu().size(); i++) {
                bottomNav.getMenu().getItem(i).setChecked(false);
            }
            bottomNav.getMenu().setGroupCheckable(0, true, true);
        }
    }

    // ... tus otros métodos navigateToHome, navigateToCalendar, etc. ...

    public void navigateToDrivers() {
        loadFragment(new com.example.wtcspaddock.ui.drivers.DriversFragment());
        // Opcional: Desmarcar botones de la barra inferior
        resetBottomNavSelection();
    }

    public void navigateToTeams() {
        loadFragment(new com.example.wtcspaddock.ui.teams.TeamsFragment());
        resetBottomNavSelection();
    }

    public void navigateToNews() {
        loadFragment(new com.example.wtcspaddock.ui.news.NewsFragment());
        resetBottomNavSelection();
    }

    // Método auxiliar para limpiar la selección de la barra inferior
    // (Añade esto para no repetir código en cada método)
    private void resetBottomNavSelection() {
        if (bottomNav != null) {
            bottomNav.getMenu().setGroupCheckable(0, true, false);
            for (int i = 0; i < bottomNav.getMenu().size(); i++) {
                bottomNav.getMenu().getItem(i).setChecked(false);
            }
            bottomNav.getMenu().setGroupCheckable(0, true, true);
        }
    }

    private void showLogoutDialog() {
        new AlertDialog.Builder(this)
                .setTitle("Logout")
                .setMessage("Are you sure you want to exit?")
                .setPositiveButton("Yes", (dialog, which) -> {
                    SessionManager session = new SessionManager(this);
                    session.logout();
                    Intent intent = new Intent(this, LoginActivity.class);
                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intent);
                })
                .setNegativeButton("Cancel", null)
                .show();
    }
}