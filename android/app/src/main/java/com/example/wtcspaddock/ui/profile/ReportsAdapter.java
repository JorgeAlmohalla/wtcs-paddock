package com.example.wtcspaddock.ui.profile;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.wtcspaddock.MainActivity;
import com.example.wtcspaddock.R;
import com.example.wtcspaddock.models.Report;

import java.util.List;

public class ReportsAdapter extends RecyclerView.Adapter<ReportsAdapter.ViewHolder> {

    private List<Report> list;

    public ReportsAdapter(List<Report> list) {
        this.list = list;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_report_row, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Report item = list.get(position);

        holder.tvStatus.setText(item.getStatus());
        holder.tvRace.setText(item.getRaceName());
        holder.tvRole.setText(item.getRole());
        holder.tvInvolved.setText(item.getInvolvedName());
        holder.tvDecision.setText(item.getDecision());

        // Colores Status
        if ("RESOLVED".equalsIgnoreCase(item.getStatus())) {
            holder.tvStatus.setBackgroundColor(Color.parseColor("#D32F2F")); // Rojo
        } else {
            holder.tvStatus.setBackgroundColor(Color.parseColor("#FFA000")); // Naranja Pending
        }

        // CLICK PARA VER DETALLE (Esto conecta con la pantalla que acabamos de hacer)
        holder.itemView.setOnClickListener(v -> {
            if (v.getContext() instanceof MainActivity) {
                ((MainActivity) v.getContext()).navigateToReportDetail(item);
            }
        });
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvStatus, tvRace, tvRole, tvInvolved, tvDecision;

        public ViewHolder(View v) {
            super(v);
            tvStatus = v.findViewById(R.id.tvReportStatus);
            tvRace = v.findViewById(R.id.tvReportRace);
            tvRole = v.findViewById(R.id.tvReportRole);
            tvInvolved = v.findViewById(R.id.tvReportInvolved);
            tvDecision = v.findViewById(R.id.tvReportDecision);
        }
    }
}