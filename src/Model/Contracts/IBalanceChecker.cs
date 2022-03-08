namespace DCA.Model.Contracts;

public interface IBalanceChecker<out T> where T : ICurrency
{
    public T GetCurrentBalance();
}
