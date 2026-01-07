package com.example.wtcspaddock.models;

import java.util.List;

public class CalendarResponse {
    // La variable DEBE llamarse "data" porque así viene en el JSON de Laravel
    private List<Race> data;

    public List<Race> getData() {
        return data;
    }
}