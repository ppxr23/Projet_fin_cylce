-- Extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_stat_statements";

-- Table User
CREATE TABLE "users" (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    roles JSONB DEFAULT '["ROLE_USER"]',
    is_active BOOLEAN DEFAULT true,
    name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Employee
CREATE TABLE employee (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL UNIQUE REFERENCES "users"(id) ON DELETE CASCADE,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    department VARCHAR(255),
    salary DECIMAL(10,2),
    manager_id UUID REFERENCES employee(id),
    hire_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Evaluation
CREATE TABLE evaluation (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    evaluator_id UUID NOT NULL REFERENCES employee(id) ON DELETE CASCADE,
    evaluated_id UUID NOT NULL REFERENCES employee(id) ON DELETE CASCADE,
    score DECIMAL(3,2) CHECK (score >= 0 AND score <= 20),
    comment TEXT,
    evaluation_date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Feedback
CREATE TABLE feedback (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    from_employee_id UUID NOT NULL REFERENCES employee(id) ON DELETE CASCADE,
    to_employee_id UUID NOT NULL REFERENCES employee(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Report
CREATE TABLE report (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    title VARCHAR(255) NOT NULL,
    content TEXT,
    report_type VARCHAR(100),
    created_by UUID NOT NULL REFERENCES "users"(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer les indexes
CREATE INDEX idx_employee_user_id ON employee(user_id);
CREATE INDEX idx_employee_manager_id ON employee(manager_id);
CREATE INDEX idx_evaluation_evaluator ON evaluation(evaluator_id);
CREATE INDEX idx_evaluation_evaluated ON evaluation(evaluated_id);
CREATE INDEX idx_evaluation_date ON evaluation(evaluation_date);
CREATE INDEX idx_feedback_from ON feedback(from_employee_id);
CREATE INDEX idx_feedback_to ON feedback(to_employee_id);
CREATE INDEX idx_report_created_by ON report(created_by);
CREATE INDEX idx_user_email ON "users"(email);

-- Insérer des données de test
INSERT INTO "users" (email, password, roles, name) VALUES
('admin@vivetic.com', '$2y$13$HyR7r7g9V5Z4c6x8k9m0d.7Z5k9m0d.7Z5k9m0d.7Z5k9m0d.7Z5k9m', '["ROLE_ADMIN"]', ''),
('manager@vivetic.com', '$2y$10$CL4afaPMVSR1hxCsq9IUpeYw9/883y3rY3Nn2x8kNXkLhb922LQTe', '["COLLABORATEUR"]', ''),
('test@test.com', '$2y$10$CL4afaPMVSR1hxCsq9IUpeYw9/883y3rY3Nn2x8kNXkLhb922LQTe', '["MANAGER"]', '');
