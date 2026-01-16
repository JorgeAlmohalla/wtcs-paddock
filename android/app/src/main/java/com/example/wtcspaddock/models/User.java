package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class User {
    private int id;
    private String name;
    private String email;

    // Campos nuevos para la edición
    @SerializedName("steam_id")
    private String steamId;

    private String nationality; // "ES"

    @SerializedName("driver_number")
    private String driverNumber;

    private String bio;
    private String equipment; // "wheel", "pad"

    @SerializedName("avatar_url")
    private String avatarUrl;

    // --- GETTERS ---
    public int getId() { return id; }
    public String getName() { return name; }
    public String getEmail() { return email; }
    public String getSteamId() { return steamId; }
    public String getNationality() { return nationality; }
    public String getDriverNumber() { return driverNumber; }
    public String getBio() { return bio; }
    public String getEquipment() { return equipment; }

    public String getAvatarUrl() {
        if (avatarUrl == null) return null;

        // 1. Si ya es una URL completa (http...), aplicamos el parche de la IP por si acaso
        if (avatarUrl.startsWith("http")) {
            if (avatarUrl.contains("127.0.0.1")) return avatarUrl.replace("127.0.0.1", "10.0.2.2");
            if (avatarUrl.contains("localhost")) return avatarUrl.replace("localhost", "10.0.2.2");
            return avatarUrl;
        }

        // 2. SI NO EMPIEZA POR HTTP (Es una ruta relativa tipo "avatars/foto.jpg")
        // Le pegamos la URL base de tu servidor.
        // OJO: Cambia la IP '192.168.1.35' por la que tengas en Constants.java o usa 10.0.2.2
        return "http://10.0.2.2:8000/storage/" + avatarUrl;
    }
}