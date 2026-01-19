package com.example.wtcspaddock.models;

import com.google.gson.annotations.SerializedName;

public class Team {
    private int id;
    private String name;
    private String car; // "Ford Mondeo Zetec"
    private String color; // Hex
    private String logo;  // URL Logo
    private String type;  // "works" o "privateer" (Asegúrate de que la API lo manda en la lista)

    // Getters
    public int getId() { return id; }
    public String getName() { return name; }
    public String getCar() { return car; }

    public String getColor() {
        if (color != null && color.length() == 4) { // Fix #666
            char r = color.charAt(1); char g = color.charAt(2); char b = color.charAt(3);
            return "#" + r + r + g + g + b + b;
        }
        return color;
    }

    public String getLogo() {
        return com.example.wtcspaddock.api.Constants.fixImageUrl(logo);
    }

    public boolean isPrivateer() { return "privateer".equalsIgnoreCase(type); }
}