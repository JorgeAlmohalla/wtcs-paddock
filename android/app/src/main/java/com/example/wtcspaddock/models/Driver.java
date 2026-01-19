package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class Driver {
    private int id;
    private String name;

    @SerializedName("nationality")
    private String nationalityCode; // "GB", "ES"

    @SerializedName("team_name")
    private String teamName;

    @SerializedName("team_color")
    private String teamColor; // "#666" o "#a30101"

    @SerializedName("avatar")
    private String avatarUrl;

    // Getters
    public int getId() { return id; }
    public String getName() { return name; }
    public String getNationalityCode() { return nationalityCode; }
    public String getTeamName() { return teamName; }

    // TRUCO: Android falla con #666. Lo convertimos a #666666
    public String getTeamColor() {
        if (teamColor != null && teamColor.length() == 4) { // ej: #666
            char r = teamColor.charAt(1);
            char g = teamColor.charAt(2);
            char b = teamColor.charAt(3);
            return "#" + r + r + g + g + b + b;
        }
        return teamColor;
    }

    public String getAvatarUrl() {
        return com.example.wtcspaddock.api.Constants.fixImageUrl(avatarUrl);
    }
}