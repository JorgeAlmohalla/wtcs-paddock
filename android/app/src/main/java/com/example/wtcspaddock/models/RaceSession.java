package com.example.wtcspaddock.models;
import java.util.List;

public class RaceSession {
    private int id;
    private String status;
    private List<ResultRow> results; // La lista de pilotos

    public List<ResultRow> getResults() { return results; }
}