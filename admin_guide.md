# Admin Guide - Primal Tutoring Services

## Overview

This guide is for administrators managing the Primal Tutoring Services platform. It covers all administrative functions and best practices.

## Accessing Admin Dashboard

1. Login with admin credentials
      The static admin credentials are:
      username: admin@gmail.com  
      password: admin123
2. You'll be redirected to `admin.php`
3. Dashboard shows tiles for different management sections


## User Management
1. In the user button, you can manage (create, edit, delete, view) the accounts (admin, tutor, student).
* If you want to have another account for admin, you can utilized the user management for admin account creation.

### Viewing Users

- Click "Users" title
- View table of all registered users
- Columns: ID, Name, Email, Role, Created Date

### Adding Users

1. Click "Add User" button
2. Fill in:
   - Name
   - Email
   - Password
   - Role (Admin, Tutor, Student)
3. Click "Add User"

### Editing Users

1. Click edit icon next to user
2. Modify details
3. Click "Update User"

### Deleting Users

1. Click delete icon next to user
2. Confirm deletion
3. Note: Deleting users will cascade delete related data (bookings, reviews, schedules)

## Subject Management

### Adding Subjects

1. Click "Subjects" tile
2. Click "Add Subject"
3. Enter subject name (e.g., "Mathematics", "Physics")
4. Click "Add Subject"

### Editing Subjects

1. Click edit icon
2. Update subject name
3. Click "Update"

### Deleting Subjects

- Click delete icon
- Confirm deletion
- Warning: Deleting subjects will remove related schedules and bookings

## Schedule Management

### Viewing Schedules

- Click "Schedules" tile
- View all tutor schedules
- Shows tutor name, subject, day, time, status

### Adding Schedules
* This is also for on-site tutors and students.

1. Click "Add Schedule"
2. Select Tutor
3. Select Subject
4. Choose Day of Week
5. Set Start and End Time
6. Click "Add Schedule"

### Editing Schedules

1. Click edit icon
2. Modify details
3. Click "Update Schedule"

### Deleting Schedules

- Click delete icon
- Confirm deletion
- Will cancel related bookings

## Booking Management

## Add Bookings
- This is for on-site booking.
- If the student is a walk-in client the admin can add it
in the users and manage booking by the admin.

### Viewing Bookings

- Click "Bookings" tile
- View all bookings with status
- Filter by status if needed

### Managing Booking Status

1. For pending bookings, click "Approve" or "Decline"
2. For approved bookings, click "Mark as Completed"
3. Status changes will notify students

### Deleting Bookings

- Click delete icon
- Use for cancelled or erroneous bookings

## Review Management

### Viewing Reviews

- Click "Reviews" tile
- See all reviews with ratings and comments

### Deleting Reviews

- Click delete icon for inappropriate content
- Confirm deletion

## Password Management

### Changing Admin Password

1. Click "Password" tile
2. Enter current password
3. Enter new password twice
4. Click "Change Password"

## Database Maintenance

### Regular Tasks

- Monitor user registrations
- Clean up old completed bookings (optional)
- Backup database regularly

### Backup Procedure

1. Use phpMyAdmin or command line:
   ```bash
   mysqldump -u username -p tutoring_system > backup.sql
   ```

2. Store backups securely

### Restore Procedure

1. Create new database or use existing
2. Import backup:
   ```bash
   mysql -u username -p tutoring_system < backup.sql
   ```

## Security Best Practices

### User Accounts

- Use strong passwords
- Regularly review user roles
- Deactivate inactive accounts

### Data Protection

- Regular backups
- Secure database credentials
- Monitor for suspicious activity

### Access Control

- Limit admin accounts
- Use HTTPS in production
- Regular security audits

## Troubleshooting

### Common Issues

**Users can't login:**
- Check account status
- Verify email/password
- Reset password if needed

**Schedules not showing:**
- Ensure tutor is assigned
- Check subject exists
- Verify time format

**Database connection errors:**
- Check config.php settings
- Verify MySQL service running
- Check database exists

### Logs and Monitoring

- Monitor web server logs
- Check PHP error logs
- Review database query logs

## Performance Optimization

### Database Optimization

- Index frequently queried columns
- Clean up old data
- Optimize queries

### Server Configuration

- Increase PHP memory limit if needed
- Configure caching
- Use CDN for static assets

## Support and Training

### Training New Admins

1. Walk through each management section
2. Explain business logic
3. Provide this guide
4. Supervise initial operations

### Getting Help

- Check this documentation first
- Review error logs
- Contact developer if needed

## Change Log

- v1.0: Initial release
- Document all major updates here

## Contact

For technical support:
- Email: admin@primaltutoring.com
- Emergency: (123) 456-7890
