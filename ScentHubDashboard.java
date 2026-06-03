package scentHub;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.awt.event.*;
import java.awt.geom.Ellipse2D;
import java.util.HashMap;
import java.util.ArrayList;
import java.util.List;

// ==================== LOGIN CLASS ====================
class Login extends JFrame implements ActionListener {

    private JTextField usernameField;
    private JPasswordField passwordField;
    private JButton loginBtn, signupBtn, forgotBtn;

    public Login() {
        setTitle("ScentHub - Professional Login");
        setSize(900, 500);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setResizable(false);

        // Main container
        JPanel mainPanel = new JPanel(new GridLayout(1, 2));

        // Left side - Branding Panel
        JPanel brandPanel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                Graphics2D g2d = (Graphics2D) g;
                GradientPaint gradient = new GradientPaint(0, 0, new Color(52, 152, 219),
                        getWidth(), getHeight(), new Color(26, 117, 200));
                g2d.setPaint(gradient);
                g2d.fillRect(0, 0, getWidth(), getHeight());
            }
        };
        brandPanel.setLayout(new BoxLayout(brandPanel, BoxLayout.Y_AXIS));
        brandPanel.setBorder(BorderFactory.createEmptyBorder(50, 30, 50, 30));

        JLabel logoLabel = new JLabel("🎯 ScentHub");
        logoLabel.setFont(new Font("Segoe UI", Font.BOLD, 36));
        logoLabel.setForeground(Color.WHITE);
        logoLabel.setAlignmentX(Component.CENTER_ALIGNMENT);

        JLabel taglineLabel = new JLabel("Premium Perfume Management");
        taglineLabel.setFont(new Font("Segoe UI", Font.PLAIN, 16));
        taglineLabel.setForeground(new Color(220, 220, 220));
        taglineLabel.setAlignmentX(Component.CENTER_ALIGNMENT);

        JLabel featureLabel1 = new JLabel("✓ Easy Inventory Management");
        featureLabel1.setFont(new Font("Segoe UI", Font.PLAIN, 13));
        featureLabel1.setForeground(new Color(230, 230, 230));
        featureLabel1.setAlignmentX(Component.CENTER_ALIGNMENT);

        JLabel featureLabel2 = new JLabel("✓ Real-time Analytics");
        featureLabel2.setFont(new Font("Segoe UI", Font.PLAIN, 13));
        featureLabel2.setForeground(new Color(230, 230, 230));
        featureLabel2.setAlignmentX(Component.CENTER_ALIGNMENT);

        JLabel featureLabel3 = new JLabel("✓ Professional Dashboards");
        featureLabel3.setFont(new Font("Segoe UI", Font.PLAIN, 13));
        featureLabel3.setForeground(new Color(230, 230, 230));
        featureLabel3.setAlignmentX(Component.CENTER_ALIGNMENT);

        brandPanel.add(Box.createVerticalGlue());
        brandPanel.add(logoLabel);
        brandPanel.add(Box.createVerticalStrut(20));
        brandPanel.add(taglineLabel);
        brandPanel.add(Box.createVerticalStrut(50));
        brandPanel.add(featureLabel1);
        brandPanel.add(Box.createVerticalStrut(15));
        brandPanel.add(featureLabel2);
        brandPanel.add(Box.createVerticalStrut(15));
        brandPanel.add(featureLabel3);
        brandPanel.add(Box.createVerticalGlue());

        // Right side - Login Form
        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBackground(new Color(245, 245, 250));
        formPanel.setBorder(BorderFactory.createEmptyBorder(40, 50, 40, 50));

        GridBagConstraints gbc = new GridBagConstraints();
        gbc.fill = GridBagConstraints.HORIZONTAL;
        gbc.insets = new Insets(10, 0, 10, 0);

        // Welcome label
        JLabel welcomeLabel = new JLabel("Welcome Back!");
        welcomeLabel.setFont(new Font("Segoe UI", Font.BOLD, 24));
        welcomeLabel.setForeground(new Color(30, 30, 30));
        gbc.gridx = 0; gbc.gridy = 0;
        gbc.gridwidth = 2;
        gbc.insets = new Insets(0, 0, 30, 0);
        formPanel.add(welcomeLabel, gbc);

        // Username
        JLabel userLabel = new JLabel("👤 Username");
        userLabel.setForeground(new Color(60, 60, 60));
        userLabel.setFont(new Font("Segoe UI", Font.BOLD, 12));
        gbc.gridy = 1;
        gbc.gridwidth = 2;
        gbc.insets = new Insets(15, 0, 5, 0);
        formPanel.add(userLabel, gbc);

        gbc.gridy = 2;
        gbc.insets = new Insets(0, 0, 15, 0);
        usernameField = createModernTextField();
        formPanel.add(usernameField, gbc);

        // Password
        JLabel passLabel = new JLabel("🔒 Password");
        passLabel.setForeground(new Color(60, 60, 60));
        passLabel.setFont(new Font("Segoe UI", Font.BOLD, 12));
        gbc.gridy = 3;
        gbc.gridwidth = 2;
        gbc.insets = new Insets(15, 0, 5, 0);
        formPanel.add(passLabel, gbc);

        gbc.gridy = 4;
        gbc.insets = new Insets(0, 0, 15, 0);
        passwordField = new JPasswordField(20);
        passwordField.setFont(new Font("Segoe UI", Font.PLAIN, 13));
        passwordField.setBackground(Color.WHITE);
        passwordField.setForeground(new Color(40, 40, 40));
        passwordField.setCaretColor(new Color(40, 40, 40));
        passwordField.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(220, 220, 220), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        formPanel.add(passwordField, gbc);

        // Forgot password link
        forgotBtn = new JButton("Forgot Password?");
        forgotBtn.setContentAreaFilled(false);
        forgotBtn.setBorderPainted(false);
        forgotBtn.setForeground(new Color(52, 152, 219));
        forgotBtn.setFont(new Font("Segoe UI", Font.PLAIN, 11));
        forgotBtn.setCursor(new Cursor(Cursor.HAND_CURSOR));
        forgotBtn.addActionListener(this);
        gbc.gridy = 5;
        gbc.gridwidth = 1;
        gbc.anchor = GridBagConstraints.EAST;
        gbc.insets = new Insets(0, 0, 20, 0);
        formPanel.add(forgotBtn, gbc);

        // Buttons panel
        JPanel btnPanel = new JPanel(new GridLayout(1, 2, 15, 0));
        btnPanel.setBackground(new Color(245, 245, 250));

        loginBtn = createModernButton("🔓 Login", new Color(52, 152, 219));
        loginBtn.addActionListener(this);

        signupBtn = createModernButton("📝 Create Account", new Color(46, 204, 113));
        signupBtn.addActionListener(this);

        btnPanel.add(loginBtn);
        btnPanel.add(signupBtn);

        gbc.gridy = 6;
        gbc.gridwidth = 2;
        gbc.insets = new Insets(20, 0, 0, 0);
        formPanel.add(btnPanel, gbc);

        mainPanel.add(brandPanel);
        mainPanel.add(formPanel);
        add(mainPanel);
        setVisible(true);
    }

    private JTextField createModernTextField() {
        JTextField field = new JTextField(20);
        field.setFont(new Font("Segoe UI", Font.PLAIN, 13));
        field.setBackground(Color.WHITE);
        field.setForeground(new Color(40, 40, 40));
        field.setCaretColor(new Color(52, 152, 219));
        field.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(220, 220, 220), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        return field;
    }

    private JButton createModernButton(String text, Color bgColor) {
        JButton btn = new JButton(text);
        btn.setFont(new Font("Segoe UI", Font.BOLD, 13));
        btn.setBackground(bgColor);
        btn.setForeground(Color.WHITE);
        btn.setFocusPainted(false);
        btn.setBorder(BorderFactory.createEmptyBorder(12, 20, 12, 20));
        btn.setCursor(new Cursor(Cursor.HAND_CURSOR));
        return btn;
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getSource() == loginBtn) {
            String username = usernameField.getText();
            String password = new String(passwordField.getPassword());

            if (username.isEmpty() || password.isEmpty()) {
                showError("Please fill in all fields!");
                return;
            }

            if (ScentHubDashboard.authenticate(username, password)) {
                showSuccess("Login successful! Welcome back.");
                dispose();
                new ScentHubDashboard();
            } else {
                showError("Invalid username or password!");
                passwordField.setText("");
            }
        } else if (e.getSource() == signupBtn) {
            dispose();
            new SignUp();
        } else if (e.getSource() == forgotBtn) {
            showInfo("Please contact support@scenthub.com to reset your password.");
        }
    }

    private void showSuccess(String msg) {
        JOptionPane.showMessageDialog(this, msg, "Success", JOptionPane.INFORMATION_MESSAGE);
    }

    private void showError(String msg) {
        JOptionPane.showMessageDialog(this, msg, "Error", JOptionPane.ERROR_MESSAGE);
    }

    private void showInfo(String msg) {
        JOptionPane.showMessageDialog(this, msg, "Information", JOptionPane.INFORMATION_MESSAGE);
    }
}

// ==================== SIGNUP CLASS ====================
class SignUp extends JFrame implements ActionListener {

    private JTextField usernameField, emailField;
    private JPasswordField passwordField, confirmPasswordField;
    private JCheckBox termsCheckBox;
    private JButton signupBtn, backBtn;

    public SignUp() {
        setTitle("ScentHub - Create Professional Account");
        setSize(820, 700);
        setLocationRelativeTo(null);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setResizable(false);

        // Main panel with two-column layout
        JPanel mainPanel = new JPanel(new GridLayout(1, 2));

        // Left side - Benefits Panel
        JPanel benefitsPanel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                Graphics2D g2d = (Graphics2D) g;
                GradientPaint gradient = new GradientPaint(0, 0, new Color(46, 204, 113),
                        getWidth(), getHeight(), new Color(26, 154, 60));
                g2d.setPaint(gradient);
                g2d.fillRect(0, 0, getWidth(), getHeight());
            }
        };
        benefitsPanel.setLayout(new BoxLayout(benefitsPanel, BoxLayout.Y_AXIS));
        benefitsPanel.setBorder(BorderFactory.createEmptyBorder(50, 30, 50, 30));

        JLabel joinLabel = new JLabel("🚀 Join ScentHub");
        joinLabel.setFont(new Font("Segoe UI", Font.BOLD, 28));
        joinLabel.setForeground(Color.WHITE);
        joinLabel.setAlignmentX(Component.CENTER_ALIGNMENT);

        JLabel taglineLabel = new JLabel("Start Managing Today");
        taglineLabel.setFont(new Font("Segoe UI", Font.PLAIN, 14));
        taglineLabel.setForeground(new Color(220, 220, 220));
        taglineLabel.setAlignmentX(Component.CENTER_ALIGNMENT);

        benefitsPanel.add(joinLabel);
        benefitsPanel.add(Box.createVerticalStrut(10));
        benefitsPanel.add(taglineLabel);
        benefitsPanel.add(Box.createVerticalStrut(60));

        String[] benefits = {
            "💼 Professional Inventory Tools",
            "📊 Real-Time Analytics Dashboard",
            "🔐 Secure Account Management",
            "📱 Multi-Device Access",
            "🎯 Business Insights Reports",
            "⚡ Lightning Fast Performance"
        };

        for (String benefit : benefits) {
            JLabel benefitLabel = new JLabel(benefit);
            benefitLabel.setFont(new Font("Segoe UI", Font.PLAIN, 13));
            benefitLabel.setForeground(Color.WHITE);
            benefitLabel.setAlignmentX(Component.CENTER_ALIGNMENT);
            benefitsPanel.add(benefitLabel);
            benefitsPanel.add(Box.createVerticalStrut(20));
        }

        benefitsPanel.add(Box.createVerticalGlue());

        // Right side - Registration Form
        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBackground(new Color(245, 245, 250));
        formPanel.setBorder(BorderFactory.createEmptyBorder(30, 40, 30, 40));

        GridBagConstraints gbc = new GridBagConstraints();
        gbc.fill = GridBagConstraints.HORIZONTAL;
        gbc.gridwidth = 2;

        // Form title
        JLabel formTitle = new JLabel("Create Your Account");
        formTitle.setFont(new Font("Segoe UI", Font.BOLD, 22));
        formTitle.setForeground(new Color(30, 30, 30));
        gbc.gridy = 0;
        gbc.insets = new Insets(0, 0, 25, 0);
        formPanel.add(formTitle, gbc);

        // Username
        JLabel userLabel = new JLabel("👤 Username");
        userLabel.setForeground(new Color(60, 60, 60));
        userLabel.setFont(new Font("Segoe UI", Font.BOLD, 11));
        gbc.gridy = 1;
        gbc.insets = new Insets(10, 0, 5, 0);
        formPanel.add(userLabel, gbc);

        gbc.gridy = 2;
        gbc.insets = new Insets(0, 0, 12, 0);
        usernameField = createFormTextField();
        formPanel.add(usernameField, gbc);

        // Email
        JLabel emailLabel = new JLabel("📧 Email Address");
        emailLabel.setForeground(new Color(60, 60, 60));
        emailLabel.setFont(new Font("Segoe UI", Font.BOLD, 11));
        gbc.gridy = 3;
        gbc.insets = new Insets(10, 0, 5, 0);
        formPanel.add(emailLabel, gbc);

        gbc.gridy = 4;
        gbc.insets = new Insets(0, 0, 12, 0);
        emailField = createFormTextField();
        formPanel.add(emailField, gbc);

        // Password
        JLabel passLabel = new JLabel("🔒 Password");
        passLabel.setForeground(new Color(60, 60, 60));
        passLabel.setFont(new Font("Segoe UI", Font.BOLD, 11));
        gbc.gridy = 5;
        gbc.insets = new Insets(10, 0, 5, 0);
        formPanel.add(passLabel, gbc);

        gbc.gridy = 6;
        gbc.insets = new Insets(0, 0, 12, 0);
        passwordField = new JPasswordField(20);
        passwordField.setFont(new Font("Segoe UI", Font.PLAIN, 12));
        passwordField.setBackground(Color.WHITE);
        passwordField.setForeground(new Color(40, 40, 40));
        passwordField.setCaretColor(new Color(46, 204, 113));
        passwordField.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(220, 220, 220), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        formPanel.add(passwordField, gbc);

        // Confirm Password
        JLabel confirmLabel = new JLabel("✓ Confirm Password");
        confirmLabel.setForeground(new Color(60, 60, 60));
        confirmLabel.setFont(new Font("Segoe UI", Font.BOLD, 11));
        gbc.gridy = 7;
        gbc.insets = new Insets(10, 0, 5, 0);
        formPanel.add(confirmLabel, gbc);

        gbc.gridy = 8;
        gbc.insets = new Insets(0, 0, 12, 0);
        confirmPasswordField = new JPasswordField(20);
        confirmPasswordField.setFont(new Font("Segoe UI", Font.PLAIN, 12));
        confirmPasswordField.setBackground(Color.WHITE);
        confirmPasswordField.setForeground(new Color(40, 40, 40));
        confirmPasswordField.setCaretColor(new Color(46, 204, 113));
        confirmPasswordField.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(220, 220, 220), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        formPanel.add(confirmPasswordField, gbc);

        // Terms Checkbox
        gbc.gridy = 9;
        gbc.insets = new Insets(15, 0, 15, 0);
        termsCheckBox = new JCheckBox("I agree to Terms & Conditions");
        termsCheckBox.setForeground(new Color(80, 80, 80));
        termsCheckBox.setFont(new Font("Segoe UI", Font.PLAIN, 11));
        termsCheckBox.setBackground(new Color(245, 245, 250));
        formPanel.add(termsCheckBox, gbc);

        // Buttons
        JPanel btnPanel = new JPanel(new GridLayout(1, 2, 10, 0));
        btnPanel.setBackground(new Color(245, 245, 250));

        backBtn = createFormButton("⬅️ Back to Login", new Color(155, 155, 155));
        backBtn.addActionListener(this);

        signupBtn = createFormButton("✅ Create Account", new Color(46, 204, 113));
        signupBtn.addActionListener(this);

        btnPanel.add(backBtn);
        btnPanel.add(signupBtn);

        gbc.gridy = 10;
        gbc.gridwidth = 2;
        gbc.insets = new Insets(10, 0, 0, 0);
        formPanel.add(btnPanel, gbc);

        mainPanel.add(benefitsPanel);
        mainPanel.add(formPanel);
        add(mainPanel);
        setVisible(true);
    }

    private JTextField createFormTextField() {
        JTextField field = new JTextField(20);
        field.setFont(new Font("Segoe UI", Font.PLAIN, 12));
        field.setBackground(Color.WHITE);
        field.setForeground(new Color(40, 40, 40));
        field.setCaretColor(new Color(46, 204, 113));
        field.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(220, 220, 220), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        return field;
    }

    private JButton createFormButton(String text, Color bgColor) {
        JButton btn = new JButton(text);
        btn.setFont(new Font("Segoe UI", Font.BOLD, 12));
        btn.setBackground(bgColor);
        btn.setForeground(Color.WHITE);
        btn.setFocusPainted(false);
        btn.setBorder(BorderFactory.createEmptyBorder(10, 15, 10, 15));
        btn.setCursor(new Cursor(Cursor.HAND_CURSOR));
        return btn;
    }

    public void actionPerformed(ActionEvent e) {
        if (e.getSource() == backBtn) {
            dispose();
            new Login();
            return;
        }

        if (e.getSource() == signupBtn) {
            String username = usernameField.getText();
            String email = emailField.getText();
            String password = new String(passwordField.getPassword());
            String confirmPassword = new String(confirmPasswordField.getPassword());
            boolean termsAgreed = termsCheckBox.isSelected();

            if (username.isEmpty() || email.isEmpty() || password.isEmpty()) {
                showError("Please fill in all fields!");
                return;
            }

            if (username.length() < 3) {
                showError("Username must be at least 3 characters!");
                return;
            }

            if (!email.contains("@") || !email.contains(".")) {
                showError("Please enter a valid email address!");
                return;
            }

            if (password.length() < 4) {
                showError("Password must be at least 4 characters!");
                return;
            }

            if (!password.equals(confirmPassword)) {
                showError("Passwords do not match!");
                confirmPasswordField.setText("");
                return;
            }

            if (!termsAgreed) {
                showError("You must agree to the Terms and Conditions!");
                return;
            }

            if (ScentHubDashboard.registerUser(username, password)) {
                showSuccess("Account created successfully!\n\nWelcome, " + username + "!");
                dispose();
                new Login();
            } else {
                showError("Username already taken!\nPlease choose another.");
            }
        }
    }

    private void showError(String msg) {
        JOptionPane.showMessageDialog(this, msg, "Registration Error", JOptionPane.ERROR_MESSAGE);
        }
    }
}

// ==================== DASHBOARD CLASS ====================
public class ScentHubDashboard extends JFrame implements ActionListener {

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
        setTitle("ScentHub - Perfume Management Dashboard");
        setSize(1400, 850);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLayout(new BorderLayout());
        setLocationRelativeTo(null);

        // Header 
        JPanel header = new JPanel(new BorderLayout());
        header.setBackground(new Color(25,25,25));
        header.setPreferredSize(new Dimension(0,60));

        JLabel title = new JLabel("  🎯 ScentHub Business Analytics Dashboard");
        title.setForeground(Color.WHITE);
        title.setFont(new Font("Segoe UI", Font.BOLD, 20));

        header.add(title, BorderLayout.WEST);

        // Sidebar 
        JPanel sidebar = new JPanel(new GridLayout(6,1,5,5));
        sidebar.setBackground(new Color(40,40,40));
        sidebar.setPreferredSize(new Dimension(180,0));
        sidebar.setBorder(BorderFactory.createEmptyBorder(10,5,10,5));

        dashBtn = createMenuBtn("📊 Dashboard");
        inventoryBtn = createMenuBtn("📦 Inventory");
        reportBtn = createMenuBtn("📈 Reports");
        settingsBtn = createMenuBtn("⚙️ Settings");
        logoutBtn = createMenuBtn("🚪 Logout");

        sidebar.add(dashBtn);
        sidebar.add(inventoryBtn);
        sidebar.add(reportBtn);
        sidebar.add(settingsBtn);
        sidebar.add(Box.createVerticalGlue());
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
        mainPanel.setBackground(new Color(240, 240, 245));

        JPanel container = new JPanel(new BorderLayout(10,10));
        container.setBackground(new Color(240, 240, 245));
        container.setBorder(BorderFactory.createEmptyBorder(10,10,10,10));

        // Info cards 
        JPanel cards = new JPanel(new GridLayout(1,4,10,10));
        cards.setBackground(new Color(240, 240, 245));
        totalLabel = new JLabel("0", JLabel.CENTER);

        cards.add(createCard("Total Products", totalLabel, new Color(52, 152, 219)));
        cards.add(createCard("Total Revenue", new JLabel("₱29,600", JLabel.CENTER), new Color(46, 204, 113)));
        cards.add(createCard("Avg Price", new JLabel("₱6,320", JLabel.CENTER), new Color(155, 89, 182)));
        cards.add(createCard("Top Brand", new JLabel("Dior", JLabel.CENTER), new Color(230, 126, 34)));

        container.add(cards, BorderLayout.NORTH);

        // Table 
        String[] cols = {"ID", "Name", "Brand", "Price"};
        model = new DefaultTableModel(cols, 0);
        table = new JTable(model);
        table.setBackground(Color.WHITE);
        table.setForeground(Color.BLACK);
        table.getTableHeader().setBackground(new Color(50, 50, 50));
        table.getTableHeader().setForeground(Color.WHITE);

        // Sample data
        model.addRow(new Object[]{"1", "Sauvage", "Dior", "6500"});
        model.addRow(new Object[]{"2", "Bleu de Chanel", "Chanel", "7200"});
        model.addRow(new Object[]{"3", "Eros", "Versace", "5800"});
        model.addRow(new Object[]{"4", "Acqua di Gio", "Armani", "6100"});
        model.addRow(new Object[]{"5", "1 Million", "Paco Rabanne", "5900"});

        // Charts Panel 
        JPanel chartsPanel = new JPanel(new GridLayout(1, 2, 10, 10));
        chartsPanel.setBackground(new Color(240, 240, 245));
        chartsPanel.setBorder(BorderFactory.createEmptyBorder(10, 0, 10, 0));

        // Pie Chart Panel
        chartsPanel.add(createPieChartPanel());

        // Bar Graph Panel
        chartsPanel.add(createBarGraphPanel());

        // Center panel with split layout
        JPanel centerPanel = new JPanel(new BorderLayout(10, 10));
        centerPanel.setBackground(new Color(240, 240, 245));
        
        JPanel tablePanel = new JPanel(new BorderLayout());
        tablePanel.setBackground(Color.WHITE);
        tablePanel.setBorder(BorderFactory.createTitledBorder(
                BorderFactory.createLineBorder(new Color(180, 180, 180), 1), 
                "Perfume Inventory", 0, 0, 
                new Font("Segoe UI", Font.BOLD, 12), Color.BLACK));
        tablePanel.add(new JScrollPane(table), BorderLayout.CENTER);
        
        centerPanel.add(chartsPanel, BorderLayout.NORTH);
        centerPanel.add(tablePanel, BorderLayout.CENTER);

        container.add(centerPanel, BorderLayout.CENTER);

        //  buttons 
        JPanel btnPanel = new JPanel(new FlowLayout(FlowLayout.LEFT, 10, 10));
        btnPanel.setBackground(new Color(240, 240, 245));

        addBtn = createActionButton("Add");
        updateBtn = createActionButton("Update");
        deleteBtn = createActionButton("Delete");
        viewBtn = createActionButton("View Details");

        addBtn.addActionListener(this);
        updateBtn.addActionListener(this);
        deleteBtn.addActionListener(this);
        viewBtn.addActionListener(this);

        btnPanel.add(addBtn);
        btnPanel.add(updateBtn);
        btnPanel.add(deleteBtn);
        btnPanel.add(viewBtn);

        container.add(btnPanel, BorderLayout.SOUTH);

        mainPanel.add(container);
        updateStats();
        refresh();
    }

    // Create Pie Chart Panel
    private JPanel createPieChartPanel() {
        JPanel panel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                Graphics2D g2d = (Graphics2D) g;
                g2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);

                // Title
                g2d.setColor(Color.BLACK);
                g2d.setFont(new Font("Segoe UI", Font.BOLD, 14));
                g2d.drawString("Brand Distribution", 20, 25);

                // Calculate data
                int[] data = {15, 20, 18, 25, 22};
                String[] labels = {"Dior", "Chanel", "Versace", "Armani", "Paco"};
                Color[] colors = {new Color(52, 152, 219), new Color(46, 204, 113), 
                                 new Color(155, 89, 182), new Color(230, 126, 34), 
                                 new Color(231, 76, 60)};

                int total = 0;
                for (int val : data) total += val;

                int centerX = getWidth() / 2;
                int centerY = getHeight() / 2 - 10;
                int radius = 80;

                double startAngle = 0;

                for (int i = 0; i < data.length; i++) {
                    double sliceAngle = (360.0 * data[i]) / total;
                    g2d.setColor(colors[i]);
                    g2d.fillArc(centerX - radius, centerY - radius, radius * 2, radius * 2, 
                               (int) startAngle, (int) sliceAngle);

                    // Draw label
                    double angle = Math.toRadians(startAngle + sliceAngle / 2);
                    int labelX = (int) (centerX + Math.cos(angle) * 120);
                    int labelY = (int) (centerY + Math.sin(angle) * 120);
                    
                    g2d.setColor(colors[i]);
                    g2d.fillRect(labelX - 4, labelY - 4, 8, 8);
                    
                    g2d.setColor(Color.BLACK);
                    g2d.setFont(new Font("Segoe UI", Font.PLAIN, 10));
                    g2d.drawString(labels[i] + " " + data[i] + "%", labelX + 8, labelY + 4);

                    startAngle += sliceAngle;
                }
            }
        };
        panel.setBackground(Color.WHITE);
        panel.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(200, 200, 200), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        panel.setPreferredSize(new Dimension(350, 280));
        return panel;
    }

    // Create Bar Graph Panel
    private JPanel createBarGraphPanel() {
        JPanel panel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                Graphics2D g2d = (Graphics2D) g;
                g2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);

                // Title
                g2d.setColor(Color.BLACK);
                g2d.setFont(new Font("Segoe UI", Font.BOLD, 14));
                g2d.drawString("Price Distribution & Trend", 20, 25);

                int[] prices = {5800, 5900, 6100, 6500, 7200};
                String[] names = {"Eros", "1M", "Acqua", "Sauvage", "Bleu"};
                Color barColor = new Color(52, 152, 219);

                int startX = 50;
                int startY = getHeight() - 70;
                int barWidth = 45;
                int spacing = 15;
                int maxPrice = 7200;

                // Draw axes
                g2d.setColor(new Color(100, 100, 100));
                g2d.setStroke(new BasicStroke(2));
                g2d.drawLine(40, startY, getWidth() - 20, startY); // X-axis
                g2d.drawLine(40, startY, 40, 40); // Y-axis

                // Draw bars
                for (int i = 0; i < prices.length; i++) {
                    int barHeight = (int) ((double) prices[i] / maxPrice * 150);
                    int x = startX + (i * (barWidth + spacing));
                    int y = startY - barHeight;

                    g2d.setColor(barColor);
                    g2d.fillRect(x, y, barWidth, barHeight);

                    g2d.setColor(new Color(150, 150, 150));
                    g2d.setStroke(new BasicStroke(1));
                    g2d.drawRect(x, y, barWidth, barHeight);

                    // Label
                    g2d.setColor(Color.BLACK);
                    g2d.setFont(new Font("Segoe UI", Font.PLAIN, 9));
                    g2d.drawString(names[i], x - 5, startY + 15);
                    g2d.drawString("₱" + prices[i], x - 10, y - 5);
                }

                // Y-axis labels
                g2d.setColor(new Color(100, 100, 100));
                g2d.setFont(new Font("Segoe UI", Font.PLAIN, 8));
                for (int i = 0; i <= 6; i++) {
                    int price = (maxPrice / 6) * i;
                    int yPos = startY - (int) ((double) price / maxPrice * 150);
                    g2d.drawString("₱" + price, 10, yPos + 3);
                }
            }
        };
        panel.setBackground(Color.WHITE);
        panel.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(200, 200, 200), 2),
                BorderFactory.createEmptyBorder(10, 10, 10, 10)));
        panel.setPreferredSize(new Dimension(350, 280));
        return panel;
    }

    // Simple pages 
    private void showPage(String name) {
        mainPanel.removeAll();

        JLabel label = new JLabel(name + " Page", JLabel.CENTER);
        label.setFont(new Font("Segoe UI", Font.BOLD, 20));

        mainPanel.add(label);
        refresh();
    }

    private JPanel createCard(String title, JLabel value, Color bgColor) {
        JPanel panel = new JPanel(new BorderLayout(5, 5));
        panel.setBackground(bgColor);
        panel.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(new Color(220, 220, 220), 2),
                BorderFactory.createEmptyBorder(15, 15, 15, 15)));

        JLabel t = new JLabel(title, JLabel.CENTER);
        t.setForeground(Color.WHITE);
        t.setFont(new Font("Segoe UI", Font.PLAIN, 12));

        value.setForeground(Color.WHITE);
        value.setFont(new Font("Segoe UI", Font.BOLD, 22));

        panel.add(t, BorderLayout.NORTH);
        panel.add(value, BorderLayout.CENTER);

        return panel;
    }

    private JButton createActionButton(String text) {
        JButton btn = new JButton(text);
        btn.setFont(new Font("Segoe UI", Font.BOLD, 11));
        btn.setBackground(new Color(52, 152, 219));
        btn.setForeground(Color.WHITE);
        btn.setFocusPainted(false);
        btn.setBorder(BorderFactory.createEmptyBorder(8, 20, 8, 20));
        btn.setCursor(new Cursor(Cursor.HAND_CURSOR));
        return btn;
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
        totalLabel.setText(String.valueOf(model.getRowCount()));
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

    public static void main(String[] args) {
        new Login();
    }
}
