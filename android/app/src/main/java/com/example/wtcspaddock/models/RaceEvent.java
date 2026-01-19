package com.example.wtcspaddock.models;

import java.util.ArrayList;
import java.util.List;

public class RaceEvent {
    private int round;
    private String trackName;
    private String countryFlag; // Si viene en tu JSON, si no, lo omitimos
    private String date;        // Usaremos la fecha de la primera sesión
    private String imageUrl;

    // Aquí guardaremos las carreras individuales (Sprint, Feature) para usarlas luego
    private List<Race> sessions = new ArrayList<>();

    public RaceEvent(int round, String trackName, String date, String imageUrl) {
        this.round = round;
        this.trackName = trackName;
        this.date = date;
        this.imageUrl = imageUrl;
    }

    public void addSession(Race race) {
        sessions.add(race);
    }

    // Getters
    public int getRound() { return round; }
    public String getTrackName() { return trackName; }
    public String getDate() { return date; }
    public String getImageUrl() {
        return com.example.wtcspaddock.api.Constants.fixImageUrl(imageUrl);
    }
    public List<Race> getSessions() { return sessions; }
}