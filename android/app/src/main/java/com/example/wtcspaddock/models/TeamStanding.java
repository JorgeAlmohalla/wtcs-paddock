package com.example.wtcspaddock.models;

public class TeamStanding {
    private int id;
    private String name;
    private String car;
    private String type; // "works" o "privateer"
    private String color; // Hex del equipo
    private int points;

    // Getters
    public int getId() { return id; }
    public String getName() { return name; }
    public String getCar() { return car; }
    public String getType() { return type; }
    public String getColor() { return color; }
    public int getPoints() { return points; }

    // Helper para saber si es privado
    public boolean isPrivateer() {
        return "privateer".equalsIgnoreCase(type);
    }
}