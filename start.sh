#!/bin/bash
# ULearn Local Development - Quick Start Script

echo "🎓 ULearn — Starting local development environment..."
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop first."
    exit 1
fi

# Build and start containers
echo "📦 Building and starting containers..."
docker compose up --build -d

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until docker compose exec db mysqladmin ping -h localhost -uroot -proot123 --silent 2>/dev/null; do
    sleep 2
    echo "   Still waiting..."
done

echo ""
echo "✅ ULearn is running!"
echo ""
echo "🌐 Open in your browser:"
echo "   Student Login:  http://localhost:8080/ulearn/index.php"
echo "   Faculty Login:  http://localhost:8080/ulearn/indexf.php"
echo ""
echo "📋 Default Credentials:"
echo "   Student:  student1 / pass123"
echo "   Faculty:  admin / admin123"
echo ""
echo "🛑 To stop: docker compose down"
