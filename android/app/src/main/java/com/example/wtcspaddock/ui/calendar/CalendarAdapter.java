package com.example.wtcspaddock.ui.calendar;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.RaceEvent;

import java.util.List;

public class CalendarAdapter extends RecyclerView.Adapter<CalendarAdapter.EventViewHolder> {

    private Context context;
    private List<RaceEvent> eventList;

    public CalendarAdapter(Context context, List<RaceEvent> eventList) {
        this.context = context;
        this.eventList = eventList;
    }

    @NonNull
    @Override
    public EventViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_race, parent, false);
        return new EventViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull EventViewHolder holder, int position) {
        RaceEvent event = eventList.get(position);

        // --- 1. TÍTULO (Circuito) ---
        if (event.getTrackName() != null) {
            holder.tvRaceTitle.setText(event.getTrackName().toUpperCase());
        } else {
            holder.tvRaceTitle.setText("UNKNOWN TRACK");
        }

        // --- 2. RONDA ---
        holder.tvRoundNumber.setText(String.valueOf(event.getRound()));

        // --- 3. FECHA (Formateada bonita) ---
        // Convertimos "2025-10-11 17:00:00" a "11 OCT 2025"
        String rawDate = event.getDate();
        String prettyDate = formatDate(rawDate);
        holder.tvRaceDate.setText(prettyDate);

        // --- 4. IMAGEN (Glide) ---
        Glide.with(context)
                .load(event.getImageUrl())
                .centerCrop()
                .placeholder(android.R.drawable.ic_menu_gallery)
                .into(holder.imgRaceTrack);

        // 5. CLICK LISTENER
        holder.itemView.setOnClickListener(v -> {
            if (context instanceof com.example.wtcspaddock.MainActivity) {
                // Llamamos al nuevo método de navegación
                ((com.example.wtcspaddock.MainActivity) context)
                        .navigateToRaceDetail(event.getRound(), event.getTrackName());
            }
        });
    }

    @Override
    public int getItemCount() {
        return eventList.size();
    }

    // --- MÉTODO AUXILIAR (Debe estar FUERA de onBindViewHolder) ---
    private String formatDate(String rawDate) {
        if (rawDate == null) return "";
        try {
            // Formato que viene de Laravel
            java.text.SimpleDateFormat inputFormat = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss", java.util.Locale.getDefault());
            // Formato que queremos mostrar
            java.text.SimpleDateFormat outputFormat = new java.text.SimpleDateFormat("dd MMM yyyy", java.util.Locale.ENGLISH);

            java.util.Date date = inputFormat.parse(rawDate);
            return outputFormat.format(date).toUpperCase();
        } catch (Exception e) {
            return rawDate; // Si falla, devolvemos la fecha original tal cual
        }
    }

    // --- VIEWHOLDER ---
    public static class EventViewHolder extends RecyclerView.ViewHolder {
        TextView tvRoundNumber, tvRaceTitle, tvRaceDate;
        ImageView imgRaceTrack;

        public EventViewHolder(@NonNull View itemView) {
            super(itemView);
            tvRoundNumber = itemView.findViewById(R.id.tvRoundNumber);
            tvRaceTitle = itemView.findViewById(R.id.tvRaceTitle);
            tvRaceDate = itemView.findViewById(R.id.tvRaceDate);
            imgRaceTrack = itemView.findViewById(R.id.imgRaceTrack);
        }
    }
}