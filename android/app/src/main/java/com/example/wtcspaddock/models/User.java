package com.example.wtcspaddock.models;
import com.google.gson.annotations.SerializedName;

public class User {
    private int id;
    private String name;
    private String email;
    private String team; // Asumo que team es un String por ahora

    @SerializedName("avatar_url") // Mapea snake_case a camelCase
    private String avatarUrl;

    // Genera los Getters (Click derecho -> Generate -> Getters)
    public String getName() { return name; }
    public String getTeam() { return team; }
    public String getAvatarUrl() { return avatarUrl; }
    // ... añade el resto si los necesitas
}