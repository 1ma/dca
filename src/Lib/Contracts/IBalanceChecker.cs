namespace DCA.Lib.Contracts;

public interface IBalanceChecker<out T> where T : ICurrency
{
    public T GetCurrentBalance();
}
