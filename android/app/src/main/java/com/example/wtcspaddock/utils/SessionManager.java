package com.example.wtcspaddock.utils;

import android.content.Context;
import android.content.SharedPreferences;

public class SessionManager {
    private static final String PREF_NAME = "WTCS_Session";
    private static final String KEY_TOKEN = "auth_token";
    private SharedPreferences prefs;
    private SharedPreferences.Editor editor;
    private static final String KEY_USER_ID = "user_id";
    private static final String KEY_AVATAR = "user_avatar";

    public SessionManager(Context context) {
        prefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
        editor = prefs.edit();
    }

    // Guardar el token (ej: "1|xyz...")
    public void saveToken(String token) {
        editor.putString(KEY_TOKEN, token);
        editor.apply(); // Guarda en segundo plano
    }

    // Obtener el token
    public String getToken() {
        return prefs.getString(KEY_TOKEN, null);
    }

    // Verificar si está logueado
    public boolean isLoggedIn() {
        return getToken() != null;
    }

    // Cerrar sesión
    public void logout() {
        editor.clear();
        editor.apply();
    }

    public void saveUserId(int id) {
        editor.putInt(KEY_USER_ID, id);
        editor.apply();
    }

    public int getUserId() {
        // Devuelve -1 si no hay ID guardado
        return prefs.getInt(KEY_USER_ID, -1);
    }

    public void saveUserAvatar(String url) {
        editor.putString(KEY_AVATAR, url);
        editor.apply();
    }

    public String getUserAvatar() {
        return prefs.getString(KEY_AVATAR, null);
    }
}