package com.example.wtcspaddock.ui.calendar;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.ResultRow;
import java.util.List;

public class ResultsAdapter extends RecyclerView.Adapter<ResultsAdapter.ViewHolder> {

    private List<ResultRow> results;
    private String sessionType; // "qualy", "sprint", "feature"

    public ResultsAdapter(List<ResultRow> results, String sessionType) {
        this.results = results;
        this.sessionType = sessionType;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_result_row, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        ResultRow row = results.get(position);

        // 1. Datos Básicos
        holder.tvPos.setText(String.valueOf(row.getPos()));
        holder.tvDriver.setText(row.getDriver());

        // Coche + Equipo
        if (row.getCar() != null) {
            holder.tvTeamCar.setText(row.getTeam() + " - " + row.getCar());
        } else {
            holder.tvTeamCar.setText(row.getTeam());
        }

        holder.tvTime.setText(row.getTime());

        // 2. Color del Equipo (Barra lateral)
        try {
            if (row.getTeamColor() != null && !row.getTeamColor().isEmpty()) {
                holder.viewTeamColor.setBackgroundColor(Color.parseColor(row.getTeamColor()));
            }
        } catch (Exception e) {
            holder.viewTeamColor.setBackgroundColor(Color.GRAY); // Fallback
        }

        holder.itemView.setOnClickListener(v -> {
            if (v.getContext() instanceof com.example.wtcspaddock.MainActivity) {
                // Navegamos al perfil usando el ID que acabamos de añadir
                ((com.example.wtcspaddock.MainActivity) v.getContext())
                        .navigateToDriverDetail(row.getDriverId());
            }
        });

        // 3. Lógica según sesión (Qualy vs Carrera)
        if (sessionType.equals("qualy")) {
            // MODO QUALY: Muestra Neumático
            holder.tvTyreBadge.setVisibility(View.VISIBLE);
            holder.tvPointsBadge.setVisibility(View.GONE);

            if (row.getTyre() != null) {
                holder.tvTyreBadge.setText(row.getTyre().toUpperCase());
                // Color neumático (Rojo Soft, Amarillo Medium)
                if (row.getTyre().equalsIgnoreCase("Soft")) {
                    holder.tvTyreBadge.setBackgroundColor(Color.parseColor("#FF5555")); // Rojo
                    holder.tvTyreBadge.setTextColor(Color.BLACK);
                } else {
                    holder.tvTyreBadge.setBackgroundColor(Color.parseColor("#FFD700")); // Amarillo
                    holder.tvTyreBadge.setTextColor(Color.BLACK);
                }
            }

        } else {
            // MODO CARRERA: Muestra Puntos
            holder.tvTyreBadge.setVisibility(View.GONE);

            // Badge Puntos (solo si > 0)
            if (row.getPoints() > 0) {
                holder.tvPointsBadge.setVisibility(View.VISIBLE);
                holder.tvPointsBadge.setText("+" + row.getPoints() + " PTS");

                // Si es vuelta rápida, color morado, si no, verde
                if (row.isFastestLap()) {
                    holder.tvPointsBadge.setBackgroundColor(Color.parseColor("#9C27B0")); // Morado
                    holder.tvPointsBadge.setText("+" + row.getPoints() + " (FL)");
                } else {
                    holder.tvPointsBadge.setBackgroundColor(Color.parseColor("#204CAF50")); // Verde oscuro
                }
            } else {
                holder.tvPointsBadge.setVisibility(View.GONE);
            }
        }

        // 4. Status (DNF / DNS)
        if (row.getTime() != null && (row.getTime().contains("DNF") || row.getTime().contains("DNS"))) {
            holder.tvTime.setTextColor(Color.parseColor("#FF5555")); // Rojo
        } else {
            holder.tvTime.setTextColor(Color.WHITE);
        }
    }

    @Override
    public int getItemCount() { return results.size(); }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvPos, tvDriver, tvTeamCar, tvTime, tvTyreBadge, tvPointsBadge;
        View viewTeamColor;

        public ViewHolder(View v) {
            super(v);
            tvPos = v.findViewById(R.id.tvPos);
            tvDriver = v.findViewById(R.id.tvDriverName);
            tvTeamCar = v.findViewById(R.id.tvTeamCar);
            tvTime = v.findViewById(R.id.tvTime);
            tvTyreBadge = v.findViewById(R.id.tvTyreBadge);
            tvPointsBadge = v.findViewById(R.id.tvPointsBadge);
            viewTeamColor = v.findViewById(R.id.viewTeamColor);
        }
    }
}