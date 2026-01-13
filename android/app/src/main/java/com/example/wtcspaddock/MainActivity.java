package com.example.wtcspaddock;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

import com.example.wtcspaddock.ui.MenuBottomSheet;
import com.example.wtcspaddock.ui.calendar.CalendarFragment;
import com.example.wtcspaddock.ui.calendar.RaceDetailFragment;
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

        // 1. Verificar Sesión
        SessionManager session = new SessionManager(this);
        if (!session.isLoggedIn()) {
            startActivity(new Intent(this, LoginActivity.class));
            finish();
            return;
        }

        // 2. Vincular Barra de Navegación
        bottomNav = findViewById(R.id.bottom_navigation);

        // 3. Configurar Listener de los 3 botones
        bottomNav.setOnItemSelectedListener(item -> {
            int itemId = item.getItemId();

            if (itemId == R.id.nav_home) {
                // IZQUIERDA: Home
                loadFragment(new com.example.wtcspaddock.ui.home.HomeFragment());
                return true;

            } else if (itemId == R.id.nav_menu_hub) {
                // CENTRO: Menú
                new com.example.wtcspaddock.ui.MenuBottomSheet().show(getSupportFragmentManager(), "WtcsMenu");
                return false;

            } else if (itemId == R.id.nav_profile) {
                // DERECHA: PERFIL PROPIO (Reutilizando DriverDetail)
                int myId = session.getUserId();

                if (myId != -1) {
                    // Cargamos el detalle del piloto con NUESTRO ID
                    loadFragment(com.example.wtcspaddock.ui.drivers.DriverDetailFragment.newInstance(myId));
                } else {
                    // Por seguridad, si no hay ID (login antiguo), mandamos al login
                    startActivity(new Intent(this, LoginActivity.class));
                    finish();
                }
                return true;
            }
            return false;
        });

        // 4. Cargar pantalla inicial (Home) al arrancar la app
        if (savedInstanceState == null) {
            loadFragment(new com.example.wtcspaddock.ui.home.HomeFragment());
        }
    }

    private void loadFragment(Fragment fragment) {
        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .addToBackStack(null) // Permite volver atrás
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

    public void performLogout() {
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

    // Método para ir al Detalle de Carrera manteniendo la barra abajo
    public void navigateToRaceDetail(int roundId, String trackName) {
        RaceDetailFragment fragment = RaceDetailFragment.newInstance(roundId, trackName);

        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .addToBackStack(null) //
                .commit();
    }

    public void navigateToDriverDetail(int driverId) {
        // Creamos el fragmento (lo programaremos en el Paso 4)
        com.example.wtcspaddock.ui.drivers.DriverDetailFragment fragment =
                com.example.wtcspaddock.ui.drivers.DriverDetailFragment.newInstance(driverId);

        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .addToBackStack(null) // Para poder volver atrás con el botón del móvil
                .commit();
    }

    // Método para ir al detalle del equipo
    public void navigateToTeamDetail(int teamId) {
        // Instanciamos el fragmento con el ID
        com.example.wtcspaddock.ui.teams.TeamDetailFragment fragment =
                com.example.wtcspaddock.ui.teams.TeamDetailFragment.newInstance(teamId);

        // Hacemos la transición
        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .addToBackStack(null) // Para poder volver atrás
                .commit();
    }

    public void navigateToNewsDetail(int newsId) {
        loadFragment(com.example.wtcspaddock.ui.news.NewsDetailFragment.newInstance(newsId));
        // Opcional: addToBackStack(null) si usas la lógica de reemplazar fragmentos con pila
        // (Recomendado si quieres volver a la lista)
    }


}