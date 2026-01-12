package com.example.wtcspaddock.ui.drivers;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.Driver;
import com.google.android.material.card.MaterialCardView;

import java.util.ArrayList;
import java.util.List;

public class DriversAdapter extends RecyclerView.Adapter<DriversAdapter.DriverViewHolder> {

    private Context context;
    private List<Driver> driversFull; // Lista completa (Copia de seguridad)
    private List<Driver> driversFiltered; // Lista que se muestra

    public DriversAdapter(Context context, List<Driver> drivers) {
        this.context = context;
        this.driversFull = new ArrayList<>(drivers); // Guardamos copia
        this.driversFiltered = drivers; // Inicialmente mostramos todos
    }

    // MÉTODO PARA FILTRAR (SOLO NOMBRE)
    public void filterList(String text) {
        List<Driver> filtered = new ArrayList<>();
        if (text == null || text.isEmpty()) {
            filtered.addAll(driversFull);
        } else {
            String filterPattern = text.toLowerCase().trim();

            for (Driver item : driversFull) {
                if (item.getName().toLowerCase().contains(filterPattern)) {
                    filtered.add(item);
                }
            }
        }
        // Actualizamos la lista visible
        this.driversFiltered = filtered;
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public DriverViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_driver_card, parent, false);
        return new DriverViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull DriverViewHolder holder, int position) {
        Driver driver = driversFiltered.get(position); // Usamos la lista filtrada

        // 1. Datos
        holder.tvName.setText(driver.getName());
        holder.tvTeam.setText(driver.getTeamName());
        holder.tvFlag.setText(getFlagEmoji(driver.getNationalityCode()));
        holder.tvInitials.setText(getInitials(driver.getName()));

        // 2. Color borde
        try {
            int color = Color.parseColor(driver.getTeamColor());
            holder.cardView.setStrokeColor(color);
        } catch (Exception e) {
            holder.cardView.setStrokeColor(Color.WHITE);
        }

        // 3. Avatar
        if (driver.getAvatarUrl() != null) {
            Glide.with(context)
                    .load(driver.getAvatarUrl())
                    .apply(RequestOptions.circleCropTransform())
                    .into(holder.imgAvatar);
            holder.tvInitials.setVisibility(View.GONE);
        } else {
            holder.imgAvatar.setImageDrawable(null);
            holder.tvInitials.setVisibility(View.VISIBLE);
        }

        // 4. Click
        holder.itemView.setOnClickListener(v -> {
            Toast.makeText(context, "Clicked: " + driver.getName(), Toast.LENGTH_SHORT).show();
        });
    }

    @Override
    public int getItemCount() {
        return driversFiltered.size();
    }

    // --- Helpers ---
    private String getInitials(String name) {
        if (name == null || name.isEmpty()) return "";
        String[] parts = name.split(" ");
        String initials = "";
        if (parts.length > 0) initials += parts[0].charAt(0);
        if (parts.length > 1) initials += parts[1].charAt(0);
        return initials.toUpperCase();
    }

    private String getFlagEmoji(String countryCode) {
        if (countryCode == null) return "";
        int firstLetter = Character.codePointAt(countryCode, 0) - 0x41 + 0x1F1E6;
        int secondLetter = Character.codePointAt(countryCode, 1) - 0x41 + 0x1F1E6;
        return new String(Character.toChars(firstLetter)) + new String(Character.toChars(secondLetter));
    }

    public static class DriverViewHolder extends RecyclerView.ViewHolder {
        MaterialCardView cardView;
        TextView tvName, tvTeam, tvInitials, tvFlag, tvRole;
        ImageView imgAvatar;

        public DriverViewHolder(@NonNull View itemView) {
            super(itemView);
            cardView = (MaterialCardView) itemView;
            tvName = itemView.findViewById(R.id.tvDriverName);
            tvTeam = itemView.findViewById(R.id.tvTeamName);
            tvInitials = itemView.findViewById(R.id.tvInitials);
            tvFlag = itemView.findViewById(R.id.tvFlag);
            tvRole = itemView.findViewById(R.id.tvRole);
            imgAvatar = itemView.findViewById(R.id.imgAvatar);
        }
    }
}