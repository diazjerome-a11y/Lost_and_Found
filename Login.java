package scentHub;

import javax.swing.*;
import java.awt.*;
import java.awt.event.*;
import java.util.HashMap;

public class Login extends JFrame implements ActionListener {

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

    public static void main(String[] args) {
        new Login();
    }
}