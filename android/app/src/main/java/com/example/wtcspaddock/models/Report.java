package com.example.wtcspaddock.models;

import java.io.Serializable;

public class Report implements Serializable {
    private int id;
    private String status;
    private String race_name;
    private String role;
    private String involved_name;
    private String decision;

    // Nuevos campos
    private String lap;
    private String description;
    private String video_url;
    private String created_at;
    private String steward_notes;

    // Getters
    public int getId() { return id; }
    public String getStatus() { return status; }
    public String getRaceName() { return race_name; }
    public String getRole() { return role; }
    public String getInvolvedName() { return involved_name; }
    public String getDecision() { return decision; }
    public String getLap() { return lap; }
    public String getDescription() { return description; }
    public String getVideoUrl() { return video_url; }
    public String getCreatedAt() { return created_at; }
    public String getStewardNotes() { return steward_notes; }
}