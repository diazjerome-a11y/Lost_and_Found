package scentHub;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.awt.event.*;
import java.util.HashMap;

// ==================== MAIN APPLICATION ====================
public class ScentHubApp {
    public static void main(String[] args) {
        new Login();
    }
}

// ==================== LOGIN CLASS ====================
class Login extends JFrame implements ActionListener {

    private JTextField usernameField;
    private JPasswordField passwordField;
    private JButton loginBtn, signupBtn;

    public Login() {
        setTitle("ScentHub - Login");
        setSize(400, 250);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLayout(new GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 10, 10, 10);

        gbc.gridx = 0; gbc.gridy = 0;
        add(new JLabel("Username:"), gbc);
        gbc.gridx = 1;
        usernameField = new JTextField(15);
        add(usernameField, gbc);

        gbc.gridx = 0; gbc.gridy = 1;
        add(new JLabel("Password:"), gbc);
        gbc.gridx = 1;
        passwordField = new JPasswordField(15);
        add(passwordField, gbc);

        gbc.gridx = 0; gbc.gridy = 2;
        loginBtn = new JButton("Login");
        loginBtn.addActionListener(this);
        add(loginBtn, gbc);

        gbc.gridx = 1;
        signupBtn = new JButton("Sign Up");
        signupBtn.addActionListener(this);
        add(signupBtn, gbc);

        setVisible(true);
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getSource() == loginBtn) {
            String username = usernameField.getText();
            String password = new String(passwordField.getPassword());

            if (ScentHubDashboard.authenticate(username, password)) {
                JOptionPane.showMessageDialog(this, "Login successful!");
                dispose();
                new ScentHubDashboard();
            } else {
                JOptionPane.showMessageDialog(this, "Invalid credentials!");
            }
        } else if (e.getSource() == signupBtn) {
            new SignUp();
        }
    }
}

// ==================== SIGNUP CLASS ====================
class SignUp extends JFrame implements ActionListener {

    private JTextField usernameField;
    private JPasswordField passwordField, confirmPasswordField;
    private JButton signupBtn;

    public SignUp() {
        setTitle("ScentHub - Sign Up");
        setSize(400, 300);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLayout(new GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 10, 10, 10);

        gbc.gridx = 0; gbc.gridy = 0;
        add(new JLabel("Username:"), gbc);
        gbc.gridx = 1;
        usernameField = new JTextField(15);
        add(usernameField, gbc);

        gbc.gridx = 0; gbc.gridy = 1;
        add(new JLabel("Password:"), gbc);
        gbc.gridx = 1;
        passwordField = new JPasswordField(15);
        add(passwordField, gbc);

        gbc.gridx = 0; gbc.gridy = 2;
        add(new JLabel("Confirm Password:"), gbc);
        gbc.gridx = 1;
        confirmPasswordField = new JPasswordField(15);
        add(confirmPasswordField, gbc);

        gbc.gridx = 0; gbc.gridy = 3; gbc.gridwidth = 2;
        signupBtn = new JButton("Sign Up");
        signupBtn.addActionListener(this);
        add(signupBtn, gbc);

        setVisible(true);
    }

    public void actionPerformed(ActionEvent e) {
        String username = usernameField.getText();
        String password = new String(passwordField.getPassword());
        String confirmPassword = new String(confirmPasswordField.getPassword());

        if (username.isEmpty() || password.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Please fill all fields!");
            return;
        }

        if (!password.equals(confirmPassword)) {
            JOptionPane.showMessageDialog(this, "Passwords do not match!");
            return;
        }

        if (ScentHubDashboard.registerUser(username, password)) {
            JOptionPane.showMessageDialog(this, "Sign up successful!");
            dispose();
        } else {
            JOptionPane.showMessageDialog(this, "Username already exists!");
        }
    }
}

// ==================== DASHBOARD CLASS ====================
class ScentHubDashboard extends JFrame implements ActionListener {

    // User storage
    private static HashMap<String, String> users = new HashMap<>();

    //container for switching pages 
    JPanel mainPanel;

    // Table for perfume data 
    JTable table;
    DefaultTableModel model;

    JButton addBtn, updateBtn, deleteBtn, viewBtn;

    // Sidebar buttons
    JButton dashBtn, inventoryBtn, reportBtn, settingsBtn, logoutBtn;

    // Dashboard card 
    JLabel totalLabel;

    public ScentHubDashboard() {

        // GUI Application
        setTitle("ScentHub - Perfume Management");
        setSize(1000, 550);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLayout(new BorderLayout());

        // Header 
        JPanel header = new JPanel(new BorderLayout());
        header.setBackground(new Color(25,25,25));
        header.setPreferredSize(new Dimension(0,50));

        JLabel title = new JLabel("  ScentHub Dashboard");
        title.setForeground(Color.WHITE);
        title.setFont(new Font("Segoe UI", Font.BOLD, 18));

        header.add(title, BorderLayout.WEST);

        // Sidebar 
        JPanel sidebar = new JPanel(new GridLayout(6,1,5,5));
        sidebar.setBackground(new Color(40,40,40));
        sidebar.setPreferredSize(new Dimension(170,0));

        dashBtn = createMenuBtn("Dashboard");
        inventoryBtn = createMenuBtn("Inventory");
        reportBtn = createMenuBtn("Reports");
        settingsBtn = createMenuBtn("Settings");
        logoutBtn = createMenuBtn("Logout");

        sidebar.add(dashBtn);
        sidebar.add(inventoryBtn);
        sidebar.add(reportBtn);
        sidebar.add(settingsBtn);
        sidebar.add(logoutBtn);

        // Main panel 
        mainPanel = new JPanel(new BorderLayout());
        showDashboard();

        add(header, BorderLayout.NORTH);
        add(sidebar, BorderLayout.WEST);
        add(mainPanel, BorderLayout.CENTER);

        setVisible(true);
    }

    // Dashboard view 
    private void showDashboard() {
        mainPanel.removeAll();

        JPanel container = new JPanel(new BorderLayout(10,10));
        container.setBorder(BorderFactory.createEmptyBorder(10,10,10,10));

        // Info cards 
        JPanel cards = new JPanel(new GridLayout(1,3,10,10));
        totalLabel = new JLabel("Total Products: 0", JLabel.CENTER);

        cards.add(createCard("Total Products", totalLabel));
        cards.add(createCard("Top Brand", new JLabel("N/A", JLabel.CENTER)));
        cards.add(createCard("Revenue", new JLabel("₱0", JLabel.CENTER)));

        container.add(cards, BorderLayout.NORTH);

        // Table 
        String[] cols = {"ID", "Name", "Brand", "Price"};
        model = new DefaultTableModel(cols, 0);
        table = new JTable(model);
        
        // Data Visualization
        JTextArea visualization = new JTextArea();
        visualization.setEditable(false);
        visualization.setText("Perfume Prices Visualization:\n");
        for (int i = 0; i < model.getRowCount(); i++) {
            String name = model.getValueAt(i, 1).toString();
            String price = model.getValueAt(i, 3).toString();
            visualization.append(name + ": " + price + "\n");
        }
        JScrollPane vizScroll = new JScrollPane(visualization);
        vizScroll.setPreferredSize(new Dimension(0, 200));

        // Sample data
        model.addRow(new Object[]{"1", "Sauvage", "Dior", "₱6500"});
        model.addRow(new Object[]{"2", "Bleu de Chanel", "Chanel", "₱7200"});
        model.addRow(new Object[]{"3", "Eros", "Versace", "₱5800"});
        model.addRow(new Object[]{"4", "Acqua di Gio", "Armani", "₱6100"});
        model.addRow(new Object[]{"5", "1 Million", "Paco Rabanne", "₱5900"});

        container.add(new JScrollPane(table), BorderLayout.CENTER);

        //  buttons 
        JPanel btnPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));

        addBtn = new JButton("Add");
        updateBtn = new JButton("Update");
        deleteBtn = new JButton("Delete");
        viewBtn = new JButton("View Details");

        addBtn.addActionListener(this);
        updateBtn.addActionListener(this);
        deleteBtn.addActionListener(this);
        viewBtn.addActionListener(this);

        btnPanel.add(addBtn);
        btnPanel.add(updateBtn);
        btnPanel.add(deleteBtn);
        btnPanel.add(viewBtn);

        JPanel bottomPanel = new JPanel(new BorderLayout());
        bottomPanel.add(vizScroll, BorderLayout.CENTER);
        bottomPanel.add(btnPanel, BorderLayout.SOUTH);

        container.add(bottomPanel, BorderLayout.SOUTH);

        mainPanel.add(container);
        updateStats();
        refresh();
    }

    // Simple pages 
    private void showPage(String name) {
        mainPanel.removeAll();

        JLabel label = new JLabel(name + " Page", JLabel.CENTER);
        label.setFont(new Font("Segoe UI", Font.BOLD, 20));

        mainPanel.add(label);
        refresh();
    }

    private JPanel createCard(String title, JLabel value) {
        JPanel panel = new JPanel(new BorderLayout());
        panel.setBackground(new Color(70,130,180));

        JLabel t = new JLabel(title, JLabel.CENTER);
        t.setForeground(Color.WHITE);

        value.setForeground(Color.WHITE);
        value.setFont(new Font("Segoe UI", Font.BOLD, 16));

        panel.add(t, BorderLayout.NORTH);
        panel.add(value, BorderLayout.CENTER);

        return panel;
    }

    private JButton createMenuBtn(String text) {
        JButton btn = new JButton(text);
        btn.setFocusPainted(false);
        btn.setBackground(new Color(60,60,60));
        btn.setForeground(Color.WHITE);
        btn.addActionListener(this);
        return btn;
    }

    private void refresh() {
        mainPanel.revalidate();
        mainPanel.repaint();
    }

    // Button logic 
    public void actionPerformed(ActionEvent e) {

        Object src = e.getSource();

        // Sidebar navigation
        if (src == dashBtn) showDashboard();
        else if (src == inventoryBtn) showPage("Inventory");
        else if (src == reportBtn) showPage("Reports");
        else if (src == settingsBtn) showPage("Settings");
        else if (src == logoutBtn) System.exit(0);

        // Add
        else if (src == addBtn) {
            JTextField id = new JTextField();
            JTextField name = new JTextField();
            JTextField brand = new JTextField();
            JTextField price = new JTextField();

            Object[] fields = {"ID:", id, "Name:", name, "Brand:", brand, "Price:", price};

            if (JOptionPane.showConfirmDialog(this, fields, "Add", JOptionPane.OK_CANCEL_OPTION)
                    == JOptionPane.OK_OPTION) {

                model.addRow(new Object[]{
                        id.getText(),
                        name.getText(),
                        brand.getText(),
                        price.getText()
                });

                updateStats();
            }
        }

        // Update 
        else if (src == updateBtn) {

            int row = table.getSelectedRow();
            if (row == -1) return;

            JTextField idField = new JTextField(model.getValueAt(row, 0).toString());
            JTextField nameField = new JTextField(model.getValueAt(row, 1).toString());
            JTextField brandField = new JTextField(model.getValueAt(row, 2).toString());
            JTextField priceField = new JTextField(model.getValueAt(row, 3).toString());

            Object[] fields = {
                    "ID:", idField,
                    "Name:", nameField,
                    "Brand:", brandField,
                    "Price:", priceField
            };

            if (JOptionPane.showConfirmDialog(this, fields, "Update", JOptionPane.OK_CANCEL_OPTION)
                    == JOptionPane.OK_OPTION) {

                model.setValueAt(idField.getText(), row, 0);
                model.setValueAt(nameField.getText(), row, 1);
                model.setValueAt(brandField.getText(), row, 2);
                model.setValueAt(priceField.getText(), row, 3);

                updateStats();
            }
        }

        // Delete
        else if (src == deleteBtn) {
            int row = table.getSelectedRow();
            if (row != -1) {
                model.removeRow(row);
                updateStats();
            }
        }

        // View Details
        else if (src == viewBtn) {
            int row = table.getSelectedRow();
            if (row == -1) return;

            JOptionPane.showMessageDialog(this,
                    "Name: " + model.getValueAt(row,1) +
                    "\nBrand: " + model.getValueAt(row,2) +
                    "\nPrice: " + model.getValueAt(row,3));
        }
    }

    private void updateStats() {
        totalLabel.setText("Total Products: " + model.getRowCount());
    }

    // User authentication methods
    public static boolean authenticate(String username, String password) {
        return users.containsKey(username) && users.get(username).equals(password);
    }

    public static boolean registerUser(String username, String password) {
        if (users.containsKey(username)) {
            return false;
        }
        users.put(username, password);
        return true;
    }
}
