package com.example.wtcspaddock.ui.standings;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.DriverStanding;
import com.example.wtcspaddock.models.ManufacturerStanding;
import com.example.wtcspaddock.models.TeamStanding;

import java.util.List;

public class StandingsAdapter extends RecyclerView.Adapter<StandingsAdapter.ViewHolder> {

    private List<Object> items; // Lista genérica

    public StandingsAdapter(List<Object> items) {
        this.items = items;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_standing_row, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Object item = items.get(position);

        // 1. Configuración Común: Posición
        holder.tvPos.setText(String.valueOf(position + 1));

        // 2. RESETEO DE ESTILOS (Vital para evitar bugs visuales al hacer scroll)
        holder.root.setBackgroundColor(Color.TRANSPARENT); // Fondo transparente por defecto
        holder.tvBadge.setVisibility(View.GONE);           // Badge oculto por defecto
        holder.itemView.setOnClickListener(null);          // Click desactivado por defecto

        // --- CASO 1: PILOTOS (Drivers) ---
        if (item instanceof DriverStanding) {
            DriverStanding driver = (DriverStanding) item;

            holder.tvTitle.setText(driver.getName());
            holder.tvSubtitle.setText(driver.getTeam());
            holder.tvPoints.setText(String.valueOf(driver.getPoints()));

            // Barra lateral blanca (o color del equipo si lo añades al modelo DriverStanding)
            holder.viewColorBar.setBackgroundColor(Color.WHITE);

            // CLICK LISTENER: Navegar al perfil
            holder.itemView.setOnClickListener(v -> {
                if (v.getContext() instanceof com.example.wtcspaddock.MainActivity) {
                    ((com.example.wtcspaddock.MainActivity) v.getContext())
                            .navigateToDriverDetail(driver.getId());
                }
            });
        }

        // --- CASO 2: EQUIPOS (Constructors) ---
        else if (item instanceof TeamStanding) {
            TeamStanding team = (TeamStanding) item;

            holder.tvTitle.setText(team.getName());
            holder.tvSubtitle.setText(team.getCar());
            holder.tvPoints.setText(String.valueOf(team.getPoints()));

            // Barra lateral con el color del equipo
            try {
                holder.viewColorBar.setBackgroundColor(Color.parseColor(team.getColor()));
            } catch (Exception e) {
                holder.viewColorBar.setBackgroundColor(Color.GRAY);
            }

            // Lógica Privateer (Fondo Azul Oscuro)
            if (team.isPrivateer()) {
                // Asegúrate de tener R.color.privateer_bg definido en colors.xml
                int privateerColor = ContextCompat.getColor(holder.itemView.getContext(), R.color.privateer_bg);
                holder.root.setBackgroundColor(privateerColor);
                holder.tvBadge.setVisibility(View.VISIBLE);
            }
        }

        // --- CASO 3: MANUFACTURERS ---
        else if (item instanceof ManufacturerStanding) {
            ManufacturerStanding manu = (ManufacturerStanding) item;

            holder.tvTitle.setText(manu.getName().toUpperCase());
            holder.tvSubtitle.setText(manu.getTeamCount() + " Team");
            holder.tvPoints.setText(String.valueOf(manu.getPoints()));

            try {
                holder.viewColorBar.setBackgroundColor(Color.parseColor(manu.getColor()));
            } catch (Exception e) {
                holder.viewColorBar.setBackgroundColor(Color.GRAY);
            }
        }
    }

    @Override
    public int getItemCount() {
        return items.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        LinearLayout root;
        TextView tvPos, tvTitle, tvSubtitle, tvPoints, tvBadge;
        View viewColorBar;

        public ViewHolder(View v) {
            super(v);
            root = v.findViewById(R.id.rowRoot);
            tvPos = v.findViewById(R.id.tvPos);
            tvTitle = v.findViewById(R.id.tvTitle);
            tvSubtitle = v.findViewById(R.id.tvSubtitle);
            tvPoints = v.findViewById(R.id.tvPoints);
            tvBadge = v.findViewById(R.id.tvBadge);
            viewColorBar = v.findViewById(R.id.viewColorBar);
        }
    }
}