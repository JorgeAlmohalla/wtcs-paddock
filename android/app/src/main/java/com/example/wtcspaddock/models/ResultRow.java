package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class ResultRow {
    private int pos;

    private String driver;

    @SerializedName("driver_id")
    private int driverId;

    // En tu última API envías el nombre del equipo como texto
    private String team;

    @SerializedName("team_color")
    private String teamColor;

    // --- ESTE ES EL CAMPO QUE DABA ERROR (Solo debe aparecer una vez) ---
    @SerializedName("team_type")
    private String teamType;
    // -------------------------------------------------------------------

    private String penalty; // "+5s", etc.

    private String time;
    private int points;
    private String car;

    @SerializedName("fastest_lap")
    private boolean fastestLap;

    private String tyre; // Para qualy

    // --- GETTERS ---
    public int getPos() { return pos; }
    public String getDriver() { return driver; }
    public int getDriverId() { return driverId; }

    // Getter inteligente para el equipo
    public String getTeam() {
        return team != null ? team : "Unknown Team";
    }

    public String getTeamColor() { return teamColor; }
    public String getTime() { return time; }
    public int getPoints() { return points; }
    public String getCar() { return car; }
    public boolean isFastestLap() { return fastestLap; }
    public String getTyre() { return tyre; }

    public String getPenalty() { return penalty; }

    // Helper para saber si es privado
    public boolean isPrivateer() {
        return "privateer".equalsIgnoreCase(teamType);
    }
}