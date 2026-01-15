package com.example.wtcspaddock.models;
import java.util.List;

public class FormData {
    public List<SimpleItem> races;
    public List<SimpleItem> drivers;

    public static class SimpleItem {
        public int id;
        public String name;

        // Sobrescribimos toString para que el Spinner muestre el nombre automáticamente
        @Override public String toString() { return name; }
    }
}