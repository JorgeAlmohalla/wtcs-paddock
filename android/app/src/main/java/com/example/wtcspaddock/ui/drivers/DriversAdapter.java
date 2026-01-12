package com.example.wtcspaddock.ui.drivers;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
// import android.widget.Toast; // Ya no lo necesitamos si navegamos de verdad

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.example.wtcspaddock.MainActivity; // IMPORTANTE
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.Driver;
import com.google.android.material.card.MaterialCardView;

import java.util.ArrayList;
import java.util.List;

public class DriversAdapter extends RecyclerView.Adapter<DriversAdapter.DriverViewHolder> {

    private Context context;
    private List<Driver> driversFull;
    private List<Driver> driversFiltered;

    public DriversAdapter(Context context, List<Driver> drivers) {
        this.context = context;
        this.driversFull = new ArrayList<>(drivers);
        this.driversFiltered = drivers;
    }

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
        Driver driver = driversFiltered.get(position);

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

        // 4. CLICK LISTENER (LA CLAVE)
        holder.itemView.setOnClickListener(v -> {
            // Verificamos que el contexto es la MainActivity para poder navegar
            if (context instanceof MainActivity) {
                ((MainActivity) context).navigateToDriverDetail(driver.getId());
            }
        });
    }

    @Override
    public int getItemCount() {
        return driversFiltered.size();
    }

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
        try {
            int firstLetter = Character.codePointAt(countryCode, 0) - 0x41 + 0x1F1E6;
            int secondLetter = Character.codePointAt(countryCode, 1) - 0x41 + 0x1F1E6;
            return new String(Character.toChars(firstLetter)) + new String(Character.toChars(secondLetter));
        } catch (Exception e) { return ""; }
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