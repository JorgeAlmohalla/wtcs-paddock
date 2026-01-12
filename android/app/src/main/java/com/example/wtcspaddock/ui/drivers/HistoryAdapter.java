package com.example.wtcspaddock.ui.drivers;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.DriverHistory;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

public class HistoryAdapter extends RecyclerView.Adapter<HistoryAdapter.ViewHolder> {

    private List<DriverHistory> uniqueRounds;

    public HistoryAdapter(List<DriverHistory> fullHistory) {
        this.uniqueRounds = new ArrayList<>();

        // MAPA INTELIGENTE: Clave = Ronda, Valor = Mejor fila de datos
        Map<Integer, DriverHistory> bestRowMap = new LinkedHashMap<>();

        if (fullHistory != null) {
            for (DriverHistory item : fullHistory) {
                int round = item.getRoundNumber();

                // ¿Tiene esta fila datos de qualy válidos?
                boolean hasQualyData = item.getQualyTime() != null && !item.getQualyTime().equals("-");

                if (!bestRowMap.containsKey(round)) {
                    // Si es la primera vez que vemos la ronda, la guardamos
                    bestRowMap.put(round, item);
                } else {
                    // Si ya la tenemos, solo la reemplazamos si la nueva TIENE datos y la vieja NO
                    DriverHistory existing = bestRowMap.get(round);
                    boolean existingHasData = existing.getQualyTime() != null && !existing.getQualyTime().equals("-");

                    if (!existingHasData && hasQualyData) {
                        bestRowMap.put(round, item); // ¡Encontrada la fila buena!
                    }
                }
            }
        }

        this.uniqueRounds.addAll(bestRowMap.values());
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_history_row, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        DriverHistory item = uniqueRounds.get(position);

        holder.tvRoundName.setText(item.getRoundName());
        holder.tvRoundNum.setText("ROUND " + item.getRoundNumber());

        // Grid Position
        if (item.getQualyPos() > 0) holder.tvGridPos.setText(String.valueOf(item.getQualyPos()));
        else holder.tvGridPos.setText("-");

        // Tiempo
        holder.tvQualyTime.setText(item.getQualyTime() != null ? item.getQualyTime() : "-");

        // Neumático (Color Dot)
        String tyre = item.getQualyTyre();
        int color = Color.parseColor("#555555"); // Gris oscuro por defecto

        if (tyre != null) {
            if (tyre.equalsIgnoreCase("Soft")) color = Color.parseColor("#F44336"); // Rojo
            else if (tyre.equalsIgnoreCase("Medium")) color = Color.parseColor("#FFEB3B"); // Amarillo
            else if (tyre.equalsIgnoreCase("Hard")) color = Color.WHITE;
            else if (tyre.equalsIgnoreCase("Wet")) color = Color.parseColor("#2196F3"); // Azul Lluvia
        }
        holder.imgTyreDot.setColorFilter(color);
    }

    @Override
    public int getItemCount() {
        return uniqueRounds.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvRoundName, tvRoundNum, tvGridPos, tvQualyTime;
        ImageView imgTyreDot;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvRoundName = itemView.findViewById(R.id.tvRoundName);
            tvRoundNum = itemView.findViewById(R.id.tvRoundNum);
            tvGridPos = itemView.findViewById(R.id.tvGridPos);
            tvQualyTime = itemView.findViewById(R.id.tvQualyTime);
            imgTyreDot = itemView.findViewById(R.id.imgTyreDot);
        }
    }
}