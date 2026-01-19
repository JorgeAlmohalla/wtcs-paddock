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
        return com.example.wtcspaddock.api.Constants.fixImageUrl(avatarUrl);
    }

}