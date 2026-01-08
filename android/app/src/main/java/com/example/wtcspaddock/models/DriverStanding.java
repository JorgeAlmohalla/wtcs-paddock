package com.example.wtcspaddock.models;

public class DriverStanding {
    private String name;  // Coincide con "name"
    private String team;  // Coincide con "team"
    private String points; // Coincide con "points" (Gson convierte 197 a "197")

    public String getName() { return name; }
    public String getTeam() { return team; }
    public String getPoints() { return points; }
}