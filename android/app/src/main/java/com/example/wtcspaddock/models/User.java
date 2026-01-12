package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class User {

    private int id;
    private String name;
    private String email;

    // Puede venir como "team" (string) o "team_name"
    private String team;

    @SerializedName("avatar_url")
    private String avatarUrl;

    // --- GETTERS (Imprescindibles) ---

    // Este es el que te está fallando. Asegúrate de que es 'public' y se llama 'getId'
    public int getId() {
        return id;
    }

    public String getName() {
        return name;
    }

    public String getEmail() {
        return email;
    }

    public String getTeam() {
        return team;
    }

    public String getAvatarUrl() {
        return avatarUrl;
    }
}