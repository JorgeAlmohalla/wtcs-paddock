package com.example.wtcspaddock;

import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
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
import com.bumptech.glide.Glide;
import com.bumptech.glide.request.target.CustomTarget;
import com.bumptech.glide.request.transition.Transition;
import android.graphics.drawable.Drawable;
import android.graphics.Bitmap;
import androidx.core.graphics.drawable.RoundedBitmapDrawable;
import androidx.core.graphics.drawable.RoundedBitmapDrawableFactory;
import android.view.MenuItem;

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
                    loadFragment(new com.example.wtcspaddock.ui.profile.MyDashboardFragment());
                    return true;
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
        loadUserProfileIcon();
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
    }

    public void navigateToReportDetail(com.example.wtcspaddock.models.Report report) {
        loadFragment(com.example.wtcspaddock.ui.profile.ReportDetailFragment.newInstance(report));
    }

    private void loadUserProfileIcon() {
        SessionManager session = new SessionManager(this);
        String avatarUrl = session.getUserAvatar();

        // Si no hay avatar, nos quedamos con el icono por defecto
        if (avatarUrl == null || avatarUrl.isEmpty()) return;

        // 1. IMPORTANTE: Desactivar el tintado automático para que se vea la foto a color
        // Ojo: Esto hará que el resto de iconos (Home, Menu) se vean con su color original del SVG (blanco).
        // Si tus SVGs son blancos, perfecto. Si son negros, no se verán en fondo oscuro.
        bottomNav.setItemIconTintList(null);

        // 2. Descargar y procesar imagen con Glide
        Glide.with(this)
                .asBitmap() // Pedimos un Bitmap para poder redondearlo
                .load(avatarUrl)
                .circleCrop() // Recorte circular
                .into(new CustomTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(@NonNull Bitmap resource, @Nullable Transition<? super Bitmap> transition) {
                        // Crear un Drawable redondo a partir del Bitmap
                        RoundedBitmapDrawable circularDrawable =
                                RoundedBitmapDrawableFactory.create(getResources(), resource);
                        circularDrawable.setCircular(true);

                        // Buscar el item del menú y cambiarle el icono
                        MenuItem profileItem = bottomNav.getMenu().findItem(R.id.nav_profile);
                        if (profileItem != null) {
                            profileItem.setIcon(circularDrawable);
                        }
                    }

                    @Override
                    public void onLoadCleared(@Nullable Drawable placeholder) {
                        // No hace falta hacer nada aquí
                    }
                });
    }


}