package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class ResultRow {
    private int pos;
    private String driver;
    private String team;

    @SerializedName("team_color")
    private String teamColor; // Ej: "#a30101"

    private String time; // Ej: "22:09.378" o "DNF"

    // Solo Carrera
    private int points;
    private String car;
    @SerializedName("fastest_lap")
    private boolean fastestLap;

    // Solo Qualy
    private String tyre; // "Soft", "Medium"

    // Getters
    public int getPos() { return pos; }
    public String getDriver() { return driver; }
    public String getTeam() { return team; }
    public String getTeamColor() { return teamColor; }
    public String getTime() { return time; }
    public int getPoints() { return points; }
    public String getCar() { return car; }
    public boolean isFastestLap() { return fastestLap; }
    public String getTyre() { return tyre; }
}