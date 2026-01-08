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

        // Posición (1, 2, 3...)
        holder.tvPos.setText(String.valueOf(position + 1));

        // Reseteamos estilos por si acaso (Reciclaje de vistas)
        holder.root.setBackgroundColor(Color.TRANSPARENT); // Fondo normal
        holder.tvBadge.setVisibility(View.GONE);

        // --- CASO 1: PILOTOS ---
        if (item instanceof DriverStanding) {
            DriverStanding driver = (DriverStanding) item;
            holder.tvTitle.setText(driver.getName());
            holder.tvSubtitle.setText(driver.getTeam());
            holder.tvPoints.setText(String.valueOf(driver.getPoints()));

            // Color barra: No tenemos color de equipo en el DriverStanding simple,
            // pero si tuvieras 'team_color', úsalo aquí. Por defecto blanco.
            holder.viewColorBar.setBackgroundColor(Color.WHITE);
        }

        // --- CASO 2: EQUIPOS (CONSTRUCTORS) ---
        else if (item instanceof TeamStanding) {
            TeamStanding team = (TeamStanding) item;

            holder.tvTitle.setText(team.getName());
            holder.tvSubtitle.setText(team.getCar());
            holder.tvPoints.setText(String.valueOf(team.getPoints()));

            // Color Barra
            try {
                holder.viewColorBar.setBackgroundColor(Color.parseColor(team.getColor()));
            } catch (Exception e) { holder.viewColorBar.setBackgroundColor(Color.GRAY); }

            // --- LA MAGIA DEL PRIVATEER ---
            if (team.isPrivateer()) {
                // Pintamos el fondo de azul oscuro
                int privateerColor = ContextCompat.getColor(holder.itemView.getContext(), R.color.privateer_bg);
                holder.root.setBackgroundColor(privateerColor);

                // Mostramos badge opcional
                holder.tvBadge.setVisibility(View.VISIBLE);
            }
        }

        // --- CASO 3: MANUFACTURERS ---
        else if (item instanceof ManufacturerStanding) {
            ManufacturerStanding manu = (ManufacturerStanding) item;

            holder.tvTitle.setText(manu.getName().toUpperCase());
            holder.tvSubtitle.setText(manu.getTeamCount() + " Teams");
            holder.tvPoints.setText(String.valueOf(manu.getPoints()));

            try {
                holder.viewColorBar.setBackgroundColor(Color.parseColor(manu.getColor()));
            } catch (Exception e) { holder.viewColorBar.setBackgroundColor(Color.GRAY); }
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